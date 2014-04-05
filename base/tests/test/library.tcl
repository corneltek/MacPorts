set autoconf [file dirname $argv0]/../../../Mk/macports.autoconf.mk
eval ::tcltest::configure $::argv

set output_file "output"
set work_dir "work"

# Set of procs used for testing.

# Sets $bindir variable from macports.autoconf.mk
# autogenerated file.
proc load_variables {pwd} {
    global autoconf bindir datadir portsrc cpwd

    if { ![file exists $autoconf] } {
        puts "ERROR: $autoconf does not exist."
        exit 1
    }

    set cpwd [file dirname [file dirname $pwd]]

    set line [get_line $autoconf "prefix*"]
    set prefix [lrange [split $line " "] 1 1]

    set line [get_line $autoconf "bindir*"]
    set bin [lrange [split $line "/"] 1 1]

    set bindir ${prefix}/${bin}
    set datadir ${prefix}/share
    set portsrc ${cpwd}/test-macports.conf

}

proc cleanup {} {
    global cpwd

    file delete -force /tmp/macports-tests
    file delete -force ${cpwd}/PortIndex ${cpwd}/PortIndex.quick
}

# Sets initial directories
proc set_dir {} {
    global datadir cpwd

    cleanup

    file mkdir /tmp/macports-tests/ports \
               /tmp/macports-tests/opt/local/etc/macports \
               /tmp/macports-tests/opt/local/share \
               /tmp/macports-tests/opt/local/var/macports/receipts \
               /tmp/macports-tests/opt/local/var/macports/registry \
               /tmp/macports-tests/opt/local/var/macports/build

    file link -symbolic /tmp/macports-tests/opt/local/share/macports $datadir/macports
    file link -symbolic /tmp/macports-tests/ports/test $cpwd/test
}

# Run portindex
proc port_index {} {
    global bindir datadir cpwd

    # Move up 2 level to run portindex.
    set path [pwd]
    cd ../..
    # Avoid warning about ports tree being old
    exec sh -c {touch */*/Portfile}

    exec ${bindir}/portindex 2>@1

    file copy ${cpwd}/sources.conf /tmp/macports-tests/opt/local/etc/macports/
    file copy ${cpwd}/PortIndex ${cpwd}/PortIndex.quick /tmp/macports-tests/ports/

    cd $path
}

# Executes port clean.
proc port_clean {pwd} {
    global bindir datadir portsrc

    set back [pwd]
    cd $pwd

    catch {exec env PORTSRC=${portsrc} ${bindir}/port clean 2>@1}
    cd $back
}

# Runs the portfile.
proc port_run {pwd} {
    global bindir datadir portsrc

    set back [pwd]
    cd $pwd

    set result [catch {exec env PORTSRC=${portsrc} ${bindir}/port -d test >&output} ]
    cd $back
    return $result
}

# Runs port trace.
proc port_trace {pwd} {
    global bindir datadir portsrc

    set back [pwd]
    cd $pwd

    set result [catch {exec env PORTSRC=${portsrc} ${bindir}/port -t test >&output 2>@1} ]
    cd $back
    return $result
}

# Installs new portfile.
proc port_install {} {
    global bindir portsrc

    set result [catch {exec env PORTSRC=${portsrc} ${bindir}/port install > output 2>@1} ]
}

# Run configure command.
proc port_config {pwd} {
    global path bindir portsrc

    set result [catch {exec env PORTSRC=${portsrc} ${bindir}/port configure 2>@1} ]
}

# Run destroot command.
proc port_destroot {pwd} {
    global path bindir portsrc work_dir output_file

    file copy -force ${path}/statefile ${work_dir}/.macports.statefile-unknown-version.state
    if { [exec id -u] == 0 } {
        exec chown macports ${work_dir}/.macports.statefile-unknown-version.state
    }
    set result [catch {exec env PORTSRC=${portsrc} ${bindir}/port destroot >$output_file 2>@1} ]
}

# Uninstalls portfile.
proc port_uninstall {} {
    global bindir portsrc

    set result [catch {exec env PORTSRC=${portsrc} ${bindir}/port uninstall > output 2>@1} ]
}

# Returns the line containint a given string
# from a given file, or -1 if nothing is found.
proc get_line {filename lookup} {
    set fp [open $filename r]

    while {[gets $fp line] != -1} {
        set line [string tolower $line]

        if {[string match $lookup $line] != 0} {
            close $fp
            return $line
        }
    }
    return -1
}

# This proc contains all the steps necesary
# to install a port and save the output to a file.
# Needed for the majority of regression tests.
proc initial_setup {} {
    global output_file work_dir path

    makeFile "" $output_file
    makeDirectory $work_dir

    load_variables $path
    set_dir
    port_index
    port_clean $path
    port_run $path
}
