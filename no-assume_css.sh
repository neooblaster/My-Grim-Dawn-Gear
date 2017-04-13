#!/bin/bash
find ./CSS -name "*.css" | xargs git update-index --no-assume-unchanged