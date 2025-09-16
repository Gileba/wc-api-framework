#!/bin/bash

# Build script for WC API Framework
# This script increments the build number and creates a zip file

set -e  # Exit on any error

# Check if we're in the right directory
if [ ! -f "wc-api-framework.php" ]; then
    echo "Error: wc-api-framework.php not found. Please run this script from the framework directory."
    exit 1
fi

# Get current build number from plugin header
CURRENT_BUILD=$(grep "\* Version:" wc-api-framework.php | sed "s/.*(build \([0-9]*\)).*/\1/")

# Validate that we got a number
if ! [[ "$CURRENT_BUILD" =~ ^[0-9]+$ ]]; then
    echo "Error: Could not extract current build number from plugin header"
    echo "Found: $CURRENT_BUILD"
    exit 1
fi

# Increment build number
NEW_BUILD=$((CURRENT_BUILD + 1))

echo "Current build: $CURRENT_BUILD"
echo "New build: $NEW_BUILD"

# Update build number in main file
echo "Updating build constant..."
sed -i '' "s/define('WC_API_FRAMEWORK_BUILD', '[^']*');/define('WC_API_FRAMEWORK_BUILD', '$NEW_BUILD');/" wc-api-framework.php

# Verify the constant was updated
UPDATED_CONSTANT=$(grep "define('WC_API_FRAMEWORK_BUILD'" wc-api-framework.php)
echo "Updated constant: $UPDATED_CONSTANT"

# Update build number in plugin header
echo "Updating plugin header..."
sed -i '' "s/\* Version: 1\.0\.0-alpha\.2 (build [0-9]*)/\* Version: 1.0.0-alpha.2 (build $NEW_BUILD)/" wc-api-framework.php

# Verify the header was updated
UPDATED_HEADER=$(grep "\* Version:" wc-api-framework.php)
echo "Updated header: $UPDATED_HEADER"

# Remove old zip if it exists
rm -f wc-api-framework.zip

# Create new zip
echo "Creating zip file..."
zip -r wc-api-framework.zip . -x "*.DS_Store" "*/.*" ".git/*" "build.sh"

echo "Created wc-api-framework.zip with build $NEW_BUILD"
