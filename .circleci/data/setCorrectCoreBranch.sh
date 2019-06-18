#!/usr/bin/env bash

ciUseCoreBranchPrefix="ci-use-core-branch:"

echo "-------------------------------------------------------------"

echo "Detecting '$ciUseCoreBranchPrefix' on last commit message..."
git log -1 --pretty=%B | grep -s $ciUseCoreBranchPrefix
if [ $? -eq 0 ]; then
    branch=$(git log -1 --pretty=%B | grep -s $ciUseCoreBranchPrefix)
    branch=$(grep -oP "(?<=$ciUseCoreBranchPrefix).*$" <<< $branch)
    startDir=$(pwd)
    echo "Detected branch: '$branch'"

    cd lib/mundipagg
    rm -rf ecommerce-module-core
    git clone https://github.com/mundipagg/ecommerce-module-core.git
    cd ecommerce-module-core
    git checkout $branch
    cd $startDir

    echo "Core branch changed to '$branch'"

else
    echo "No '$ciUseCoreBranchPrefix' detected on last commit message."
fi

echo "-------------------------------------------------------------"
