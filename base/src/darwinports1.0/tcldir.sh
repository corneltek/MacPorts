#!/bin/sh

# Just tell us where the Tcl installation directory is.

case `uname -s` in
        Darwin) echo /System/Library/Tcl/8.3/darwinports1.0 ;;
	Linux) echo /usr/lib/tcl8.3/darwinports1.0 ;;
	NetBSD) echo /usr/pkg/lib/tcl8.3/darwinports1.0 ;;
        *) echo /usr/local/lib/tcl8.3/darwinports1.0 ;;
esac
