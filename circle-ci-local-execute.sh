#!/usr/bin/env bash

circleci config process .circleci/config.yml > .circleci/config_local.yml
circleci local execute -c .circleci/config_local.yml --job build_and_test
