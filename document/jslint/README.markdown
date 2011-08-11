I just realized that there is a rather well hidden javascript interpreter in the mac os x terminal out of the box. /System/Library/Frameworks/JavaScriptCore.framework/Versions/A/Resources/jsc
See <http://www.phpied.com/javascript-shell-scripting/>

Then i figured it coud be quite easy to set up a command line util to run jslint from anywhere. Thought i´d share how.

## Setup ##
* Download and unzip this gist.
* Download Douglas Crockfords jslint from here <https://github.com/douglascrockford/JSLint/raw/master/fulljslint.js>.
* Edit the 'jslint' shell script and set the paths to where you saved the .js files.
* Chmod the 'jslint' shell script  (`chmod +x` )
* (optional) add an alias to your .bashrc : `alias jslint <path-to-files>/jslint `
* Try it!

## Invocation ##
Examples of valid invocations
 `jslint filename.js` ,
 `jslint filename1.js filename2.js` ,
 `jslint folder/*.js folder2/*.js` ,
