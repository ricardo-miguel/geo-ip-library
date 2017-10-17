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

echo "PROJECT_ROOT: $PROJECT_ROOT"
echo "PLUGIN_SOURCE: $PLUGIN_SOURCE"
echo "VERSION: $VERSION"
echo "ZIP TO BUILD: $ZIP_FILE"

# Build plugin zip at project root
zip -r $ZIP_FILE $PLUGIN_SOURCE -x "lib/geoiploc.tar.gz"

# Ensure the zip file for the current version has been built
if [ ! -f "$ZIP_FILE" ]; then
    echo "Built zip file $ZIP_FILE does not exist" 1>&2
    exit 1
fi

# Check if the tag exists for the version we are building
TAG=$(svn ls "https://plugins.svn.wordpress.org/$WP_PLUGIN_SLUG/tags/$VERSION")
error=$?
if [ $error == 0 ]; then
    # Tag exists, don't deploy
    echo "Tag already exists for version $VERSION, aborting deployment."
    exit 1
fi

# Proceed to build SVN
mkdir "$PROJECT_ROOT/build"
cd "$PROJECT_ROOT/build"

# Unzip the built plugin
unzip -q -o "$ZIP_FILE"

# Checkout the SVN repo
svn co -q "http://svn.wp-plugins.org/$WP_PLUGIN_SLUG" svn

# Move out the trunk directory to a temp location
mv svn/trunk ./svn-trunk

# Create trunk directory
mkdir svn/trunk

# Copy our new version of the plugin into trunk
rsync -r -p src/* svn/trunk

# Copy all the .svn folders from the checked out copy of trunk to the new trunk.
# This is necessary as the Travis container runs Subversion 1.6 which has .svn dirs in every sub dir
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
rm -fR svn-trunk

# Add new version tag
mkdir svn/tags/$VERSION
rsync -r -p $WP_PLUGIN_SLUG/* svn/tags/$VERSION

# Add new files to SVN
svn stat svn | grep '^?' | awk '{print $2}' | xargs -I x svn add x@
# Remove deleted files from SVN
svn stat svn | grep '^!' | awk '{print $2}' | xargs -I x svn rm --force x@
svn stat svn

# Commit to SVN
svn ci --no-auth-cache --username $WP_SVN_USER --password $WP_SVN_PASSWORD svn -m "Deploy version $VERSION"

# Remove SVN temp dir
rm -fR svn