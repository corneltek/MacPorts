# global port routines
# the 'main' target is provided by this package
# main is a magic target and should not be replaced
package provide portmain 1.0
package require portutil 1.0

register com.apple.main target build main
register com.apple.main provides main

global main_opts
global targets

puts $sysportpath
default distpath distfiles
default prefix /usr/local/
default workdir work
default filedir files
default portrevision 0

# define options
options portname portversion portrevision categories maintainers workdir worksrcdir no_worksubdir filedir distname

proc main {args} {
    global main_opts portname distname

    if {[tbool opts no_worksubdir]} {
	default opts worksrcdir ""
    } else {
	default opts worksrcdir distname
    }
    return 0
}

