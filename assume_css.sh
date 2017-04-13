#!/bin/bash
find ./CSS -name "*.css" | xargs git update-index --assume-unchanged