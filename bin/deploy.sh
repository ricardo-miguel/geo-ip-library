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

echo "== Environment variables"
echo "PROJECT_ROOT: $PROJECT_ROOT"
echo "PLUGIN_SOURCE: $PLUGIN_SOURCE"
echo "VERSION: $VERSION"
echo "ZIP TO BUILD: $ZIP_FILE"

echo " "
echo "== Starting deploy"
echo " "

# Check if the tag exists for the version we are building
echo "== STEP 1: Checking WordPress SVN version tag"
TAG=$(svn ls "https://plugins.svn.wordpress.org/$WP_PLUGIN_SLUG/tags/$VERSION")
error=$?
if [ $error == 0 ]; then
    # Tag exists, don't deploy
    echo "Tag already exists for version $VERSION, aborting deployment."
    exit 1
fi

# Proceed to build local SVN
echo "== STEP 2: Creating build folder container to deploy"
mkdir "$PROJECT_ROOT/build"
cd "$PROJECT_ROOT/build"
echo $PWD

# Import the SVN repository
echo "== STEP 3: Importing remote SVN repository"
svn co "http://svn.wp-plugins.org/$WP_PLUGIN_SLUG"

echo "== STEP 4: Switching to SVN copy folder"
cd "$WP_PLUGIN_SLUG"
echo $PWD

# Clean up trunk and copy new source code
echo "== STEP 5: Clean up trunk and copy new source code"
rm -r trunk/*
cp -R $PLUGIN_SOURCE/* trunk

echo "== STEP 6: Also, copy version tag"
mkdir -p tags/$VERSION
cp -R $PLUGIN_SOURCE/* tags/$VERSION

echo "== STEP 7: Commit new release"
svn ci --no-auth-cache --username $WP_SVN_USER --password $WP_SVN_PASSWORD . -m "Deploy version $VERSION"