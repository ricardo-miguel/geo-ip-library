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

where php