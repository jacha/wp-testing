#!/usr/bin/env bash

# Exit immediately if a command exits with a non-zero status.
set -e

# Define vars
HERE=$(dirname $(realpath $0))
TRAVIS_BUILD_DIR=$(realpath $HERE/../..)
export IS_APT_UPDATE=0

# Prepare environment
cd $TRAVIS_BUILD_DIR
tests/integration-environment/create.sh
cd $TRAVIS_BUILD_DIR/tests/mocha
npm install
export PATH=$PATH:./node_modules/.bin/
export TZ="UTC"

# Run tests
mocha-casperjs --grep=Plugin_activation --timeout=360000
mocha-casperjs --grep=Plugin --invert
