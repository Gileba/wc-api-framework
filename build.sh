#!/bin/bash

# Build script for WC API Framework
# This script increments the build number and creates a zip file

set -e  # Exit on any error

# Check if we're in the right directory
if [ ! -f "wc-api-framework.php" ]; then
    echo "Error: wc-api-framework.php not found. Please run this script from the framework directory."
    exit 1
fi

# Get current version and build number from plugin header
VERSION_LINE=$(grep "\* Version:" wc-api-framework.php)
echo "Found version line: $VERSION_LINE"

# Extract the full version (e.g., "1.0.0-alpha.2")
CURRENT_VERSION=$(echo "$VERSION_LINE" | sed "s/.*Version: \([^(]*\).*/\1/" | xargs)
echo "Current version: $CURRENT_VERSION"

# Extract current build number
CURRENT_BUILD=$(echo "$VERSION_LINE" | sed "s/.*(build \([0-9]*\)).*/\1/")

# Validate that we got a number
if ! [[ "$CURRENT_BUILD" =~ ^[0-9]+$ ]]; then
    echo "Error: Could not extract current build number from plugin header"
    echo "Found: $CURRENT_BUILD"
    echo "Version line: $VERSION_LINE"
    exit 1
fi

# Increment build number
NEW_BUILD=$((CURRENT_BUILD + 1))

echo "Current build: $CURRENT_BUILD"
echo "New build: $NEW_BUILD"

# Validate version format (basic check)
if [[ -z "$CURRENT_VERSION" ]]; then
    echo "Error: Could not extract version from plugin header"
    echo "Version line: $VERSION_LINE"
    exit 1
fi

echo "Version format validation passed: $CURRENT_VERSION"

# Update build number in main file
echo "Updating build constant..."
sed -i '' "s/define('WC_API_FRAMEWORK_BUILD', '[^']*');/define('WC_API_FRAMEWORK_BUILD', '$NEW_BUILD');/" wc-api-framework.php

# Verify the constant was updated
UPDATED_CONSTANT=$(grep "define('WC_API_FRAMEWORK_BUILD'" wc-api-framework.php)
echo "Updated constant: $UPDATED_CONSTANT"

# Update build number in plugin header
echo "Updating plugin header..."
# Escape special characters in the version for sed
ESCAPED_VERSION=$(echo "$CURRENT_VERSION" | sed 's/[[\.*^$()+?{|]/\\&/g')
sed -i '' "s/\* Version: $ESCAPED_VERSION (build [0-9]*)/\* Version: $CURRENT_VERSION (build $NEW_BUILD)/" wc-api-framework.php

# Verify the header was updated
UPDATED_HEADER=$(grep "\* Version:" wc-api-framework.php)
echo "Updated header: $UPDATED_HEADER"

# Remove old zip if it exists
rm -f wc-api-framework.zip

# Create new zip
echo "Creating zip file..."
zip -r wc-api-framework.zip . -x "*.DS_Store" "*/.*" ".git/*" "build.sh"

echo "Created wc-api-framework.zip with build $NEW_BUILD"
