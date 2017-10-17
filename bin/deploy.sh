#!/usr/bin/env bash

if [[ -z "$WP_PLUGIN_SLUG" ]]; then
	echo "WordPress plugin slug not set" 1>&2
	exit 1
fi

if [[ -z "$WP_SVN_USER" ]]; then
	echo "WordPress SVN user not set" 1>&2
	exit 1
fi

if [[ -z "$WP_SVN_PASSWORD" ]]; then
	echo "WordPress SVN password not set" 1>&2
	exit 1
fi

if [[ -z "$TRAVIS_BRANCH" || "$TRAVIS_BRANCH" != "testing" ]]; then
	echo "Build branch is required and must be 'master'" 1>&2
	exit 0
fi


PROJECT_ROOT="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"
PLUGIN_SOURCE="$PROJECT_ROOT/src"
VERSION=$(php -f "$PROJECT_ROOT/version.php")
ZIP_FILE="$PROJECT_ROOT/$WP_PLUGIN_SLUG-$VERSION.zip"

echo "== DEPLOY ENVIRONMENT VARIABLES"
echo "PROJECT_ROOT: $PROJECT_ROOT"
echo "PLUGIN_SOURCE: $PLUGIN_SOURCE"
echo "VERSION: $VERSION"
echo "ZIP TO BUILD: $ZIP_FILE"
echo "=="

echo " "
echo "== All project variables available"
echo "== Starting deploy"
echo " "

# Build plugin zip at project root
echo "== STEP 1: Compressing plugin source"
zip -r $ZIP_FILE $PLUGIN_SOURCE -x "src/lib/geoiploc.tar.gz"

# Ensure the zip file for the current version has been built
echo "== STEP 2: Checking plugin zipped build"
if [ ! -f "$ZIP_FILE" ]; then
    echo "Built zip file $ZIP_FILE does not exist" 1>&2
    exit 1
fi

# Check if the tag exists for the version we are building
echo "== STEP 3: Checking WordPress SVN version tag"
TAG=$(svn ls "https://plugins.svn.wordpress.org/$WP_PLUGIN_SLUG/tags/$VERSION")
error=$?
if [ $error == 0 ]; then
    # Tag exists, don't deploy
    echo "Tag already exists for version $VERSION, aborting deployment."
    exit 1
fi

# Proceed to build SVN
echo "== STEP 4: Creating build folder container to deploy"
mkdir "$PROJECT_ROOT/build"
cd "$PROJECT_ROOT/build"

# Unzip the built plugin
echo "== STEP 5: Unzipping plugin source build into /build"
unzip -q -o "$ZIP_FILE" -d "$PROJECT_ROOT/build"

# Checkout the SVN repo
echo "== STEP 6: Checking out (importing) SVN repository"
svn co -q "http://svn.wp-plugins.org/$WP_PLUGIN_SLUG" svn

# Move out the trunk directory to a temp location
echo "== STEP 7: Move out trunk directory to a temp one"
mv svn/trunk ./svn-trunk

# Create trunk directory
echo "== STEP 8: Create new trunk folder"
mkdir -p svn/trunk

# Copy our new version of the plugin into trunk
echo "== STEP 9: Move new plugin source to trunk"
rsync -r -p src/* svn/trunk

# Copy all the .svn folders from the checked out copy of trunk to the new trunk.
# This is necessary as the Travis container runs Subversion 1.6 which has .svn dirs in every sub dir
echo "== STEP 10: Copy necessary .svn files (recursive to subfolders)"
cd svn/trunk/
TARGET=$(pwd)
cd ../../svn-trunk/

# Find all .svn dirs in sub dirs
SVN_DIRS=`find . -type d -iname .svn`

for SVN_DIR in $SVN_DIRS; do
    SOURCE_DIR=${SVN_DIR/.}
    TARGET_DIR=$TARGET${SOURCE_DIR/.svn}
    TARGET_SVN_DIR=$TARGET${SVN_DIR/.}
    if [ -d "$TARGET_DIR" ]; then
        # Copy the .svn directory to trunk dir
        cp -r $SVN_DIR $TARGET_SVN_DIR
    fi
done

# Back to builds dir
cd ../

# Remove checked out dir
echo "== STEP 11: Remove old trunk"
rm -fR svn-trunk

# Add new version tag
echo "== STEP 12: New SVN version tag"
mkdir -p svn/tags/$VERSION
rsync -r -p src/* svn/tags/$VERSION

# Add new files to SVN
svn stat svn | grep '^?' | awk '{print $2}' | xargs -I x svn add x@
# Remove deleted files from SVN
svn stat svn | grep '^!' | awk '{print $2}' | xargs -I x svn rm --force x@
svn stat svn

# Commit to SVN
echo "== STEP 13: Commit to WordPress SVN repository"
svn ci --no-auth-cache --username $WP_SVN_USER --password $WP_SVN_PASSWORD svn -m "Deploy version $VERSION"

# Remove SVN temp dir
rm -fR svn