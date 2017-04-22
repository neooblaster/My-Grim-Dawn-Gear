#!/bin/bash
find ./SQL -name "*.sh" | xargs git update-index --assume-unchanged