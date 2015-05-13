Wordpress install for our global community chapters


Using travis to deploy to wpengine

[![Build Status](https://travis-ci.org/SingularityUniversity/community-chapters.svg?branch=master)](https://travis-ci.org/SingularityUniversity/community-chapters)

Current status: this is a work in progress. As of 4/15/2015 we are planning to test it with some alpha users soon.


## How does this code get deployed?

### Staging first
The `master` branch of this repo is the primary source for the code for this project. When we push to `master` or when we merge in pull requests into `master`, Travis-CI will trigger then automatically deploy our code to the staging site on wpengine. Make sure that any code that you push to `master` is ready to be put on our wpengine staging server.


### What about production?
We deploy to production via wpengine. We don't deploy to production from here.
