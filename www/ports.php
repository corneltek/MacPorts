<?php
    /* -*- coding: utf-8; mode: php; tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- vim:set fenc=utf-8 filetype=php et sw=4 ts=4 sts=4: */
    /* Web client to the PortIndex2MySQL script located in MacPorts base/portmgr/jobs svn directory. */
    /* $Id$ */
    /* Copyright (c) 2004, OpenDarwin. */
    /* Copyright (c) 2004-2007, The MacPorts Project. */
    
    include_once("includes/common.inc");
    
    $portsdb_info = portsdb_connect($portsdb_host, $portsdb_user, $portsdb_passwd);
    $sql = "SELECT UNIX_TIMESTAMP(activity_time) FROM $portsdb_name.log ORDER BY UNIX_TIMESTAMP(activity_time) DESC";
    $result = mysql_query($sql);
    if ($result && $row = mysql_fetch_row($result)) {
        $date = date('Y-m-d', $row[0]);
        $time = date('H:i:s e', $row[0]);
    } else {
        $date = '(unknown)';
        $time = '(unknown)';
    }
    $by = isset($_GET['by']) ? $_GET['by'] : '';
    $substr = isset($_GET['substr']) ? $_GET['substr'] : '';
    $page = isset($_GET['page']) ? max(intval($_GET['page']), 1) : '1';
    $pagesize = isset($_GET['pagesize']) ? max(intval($_GET['pagesize']), 1) : 50; # arbitrary setting

    print_header('The MacPorts Project -- Available Ports', 'utf-8');
?>

<div id="content">

    <h2 class="hdr">MacPorts Portfiles</h2>

    <p>The MacPorts Project currently distributes <b><?php print $portsdb_info['num_ports']; ?></b> ports, organized across
    <?php print $portsdb_info['num_categories']; ?> different categories and available below for viewing. This form allows
    you to search the MacPorts software index, last updated on <?php print '<b>' . $date . '</b> at <b>' . $time . '</b>'; ?>.
    </p>
    
    <br />

    <form action="<?php print $_SERVER['PHP_SELF']; ?>" method="get">
        <p>
            <label>Search by:</label>
            <select name="by">
                <option value="name"<?php if ($by == 'name') { print ' selected="selected"'; } ?>>Software Title</option>
                <option value="category"<?php if ($by == 'category') { print ' selected="selected"'; } ?>>Category</option>
                <option value="maintainer"<?php if ($by == 'maintainer') { print ' selected="selected"'; } ?>>Maintainer</option>
<!--                <option value="library"<?php if ($by == 'dependency') { print ' selected="selected"'; } ?>>Dependency</option> -->
                <option value="variant"<?php if ($by == 'variant') { print ' selected="selected"'; } ?>>Variant</option>
                <option value="platform"<?php if ($by == 'platform') { print ' selected="selected"'; } ?>>Platform</option>
<!--                <option value="license"<?php if ($by == 'license') { print ' selected="selected"'; } ?>>License</option> -->
            </select>
            <input type="text" name="substr" size="40" />
            <input type="submit" value="Search" />
        </p>
    </form>

    <p>Or view the complete <a href="<?php print $_SERVER['PHP_SELF']; ?>?by=all">ports list (<?php print $portsdb_info['num_ports']; ?>
    ports)</a>.</p>
    <br />

<?php
    /* If no valid search criteria is specified (by set and not equal to all and a valid substring) we output port categories
     as possible searches */
    if (!$by || ($by != 'all' && !$substr)) {
        $query = "SELECT DISTINCT category FROM $portsdb_name.categories ORDER BY category";
        $result = mysql_query($query);
        if ($result) {
            $max_entries_per_column = floor($portsdb_info['num_categories']/4);
            $columns = 0;
            print '<h3>Port Categories</h3><div id="categories"><ul>';
            while ($columns < 4) {
                $entries_per_column = 0;
                print '<li><ul>';
                while ($row = mysql_fetch_assoc($result)) {
                    print "<li><a href=\"$_SERVER[PHP_SELF]?by=category&amp;substr=" . urlencode($row['category']) . '">'
                    . htmlspecialchars($row['category']) . '</a></li>';
                    if ($entries_per_column == $max_entries_per_column) break;
                    $entries_per_column++;
                }
                print '</ul></li>';
                $columns++;
            }
            print '</ul></div>';
        }
    }

    /* If valid criteria is specified (by set and a valid substring, or by set to all) we poll the db and display the results */
    if ($by && ($substr || $by == 'all')) {
        $fields = 'name, path, version, description';
        $tables = "$portsdb_name.portfiles AS p";
        switch ($by) {
        case 'name':
            $criteria = "p.name LIKE '%" . mysql_real_escape_string($substr) . "%'";
            break;
        case 'category':
            $tables .= ", $portsdb_name.categories AS c";
            $criteria = "c.portfile = p.name AND c.category = '" . mysql_real_escape_string($substr) . "'";
            break;
        case 'maintainer':
            $tables .= ", $portsdb_name.maintainers AS m";
            $criteria = "m.portfile = p.name AND m.maintainer LIKE '%" . mysql_real_escape_string($substr) . "%'";
            break;
        case 'library':
            $criteria = "p.name = '" . mysql_real_escape_string($substr) . "'";
            break;
        case 'variant':
            $tables .= ", $portsdb_name.variants AS v";
            $criteria = "v.portfile = p.name AND v.variant = '" . mysql_real_escape_string($substr) . "'";
            break;
        case 'platform':
            $tables .= ", $portsdb_name.platforms AS pl";
            $criteria = "pl.portfile = p.name AND pl.platform = '" . mysql_real_escape_string($substr) . "'";
            break;
/*
        case 'license':
            $tables .= ", $portsdb_name.licenses AS lc";
            $criteria = "lc.portfile = p.name AND lc.license = '" . mysql_real_escape_string($substr) . "'";
            break;
*/
        case 'all':
            $criteria = '';
            break;
        default:
            $criteria = '0';
            break;
        }
        $where = ($criteria == '' ? '' : "WHERE $criteria");
        $query = "SELECT DISTINCT $fields FROM $tables $where ORDER BY name";
        $result = mysql_query($query);
        
        /* Main query sent to the DB */
        if ($result) {
            $paging = false;
            $numrows = mysql_num_rows($result);
            if ($numrows > $pagesize) {
                $paging = true;
                $pagecount = ceil($numrows / $pagesize);
                $page = min($page, $pagecount);
                $offset = $pagesize * ($page-1);
                $curpagesize = min($pagesize, $numrows - $offset);
                # generate a paging control and cache it so we can show it twice
                $pagecontrol = "<p>Page ";
                for ($i = 1; $i <= $pagecount; $i++) {
                    if ($i != 1) {
                        $pagecontrol .= " | ";
                    }
                    if ($i == $page) {
                        $pagecontrol .= "<b>$i</b>";
                    } else {
                        $pagecontrol .= "<a href=\"$_SERVER[PHP_SELF]?by=$by&amp;substr=" . urlencode($substr) . "&amp;page=$i&amp;pagesize=$pagesize\">$i</a>";
                    }
                }
                $pagecontrol .= "</p>";

                # seek the data pointer
                mysql_data_seek($result, $offset);
            }

            print '<h3>Query Results</h3>';
            if ($paging) {
                print $pagecontrol;
                $numrowtext = ($offset+1) . "-" . ($offset + $curpagesize) . " of $numrows Portfile" . ($curpagesize == 1 ? '' : 's');
            } else {
                $numrowtext = "$numrows Portfile" . ($numrows == 1 ? '' : 's');
            }
            print "<p><i>$numrowtext Selected</i></p>";

            print '<dl>';
            /* Iterate over the entire set of returned ports */
            for ($row = mysql_fetch_assoc($result), $i = 0;
                 $row && (!$paging || $i < $curpagesize);
                 $row = mysql_fetch_assoc($result), $i++) {

                /* Port name and Portfile URL */
                print "<dt><b><a href=\"${trac_url}browser/trunk/dports/" . $row['path'] . "/Portfile\">" . htmlspecialchars($row['name'])
                . '</a></b> ' . htmlspecialchars($row['version']) . '</dt>';
                
                print '<dd>';
                /* Port short description */
                print htmlspecialchars($row['description']) . '<br />';
                
                /* Licenses */
                $nquery = "SELECT license FROM $portsdb_name.licenses WHERE portfile='" . mysql_real_escape_string($row['name']) .
                "' ORDER BY license";
                $nresult = mysql_query($nquery);
                if ($nresult && mysql_num_rows($nresult) > 0) {
                    print '<i>Licenses:</i> ';
                    while ($nrow = mysql_fetch_row($nresult)) {
                        print "<a href=\"$_SERVER[PHP_SELF]?by=license&amp;substr=" . urlencode($nrow[0]) . '">'
                        . htmlspecialchars($nrow[0]) . '</a> ';
                    }
                    print "<br />";
                }

                /* Maintainers */
                $nquery = "SELECT maintainer FROM $portsdb_name.maintainers WHERE portfile='" . mysql_real_escape_string($row['name']) .
                "' ORDER BY is_primary DESC, maintainer";
                $nresult = mysql_query($nquery);
                if ($nresult) {
                    $primary = 1;
                    print '<i>Maintained by:</i>';
                    while ($nrow = mysql_fetch_row($nresult)) {
                        if ($primary) { print ' <b>'; }
                        else { print ' '; }
                        print obfuscate_email($nrow[0]);
                        if ($primary) { print '</b>'; }
                        $primary = 0;
                    }
                }

                /* Categories */
                $nquery = "SELECT category FROM $portsdb_name.categories WHERE portfile='" . mysql_real_escape_string($row['name']) .
                "' ORDER BY is_primary DESC, category";
                $nresult = mysql_query($nquery);
                if ($nresult) {
                    $primary = 1;
                    print '<br /><i>Categories:</i>';
                    while ($nrow = mysql_fetch_row($nresult)) {
                        if ($primary) { print ' <b>'; }
                        else { print ' '; }
                        print "<a href=\"$_SERVER[PHP_SELF]?by=category&amp;substr=" . urlencode($nrow[0]) . '">'
                        . htmlspecialchars($nrow[0]) . '</a>';
                        if ($primary) { print '</b>'; }
                        $primary = 0;
                    }
                }

                /* Platforms */
                $nquery = "SELECT platform FROM $portsdb_name.platforms WHERE portfile='" . mysql_real_escape_string($row['name']) .
                "' ORDER BY platform";
                $nresult = mysql_query($nquery);
                if ($nresult) {
                    print '<br /><i>Platforms:</i> ';
                    while ($nrow = mysql_fetch_row($nresult)) {
                        print "<a href=\"$_SERVER[PHP_SELF]?by=platform&amp;substr=" . urlencode($nrow[0]) . '">'
                        . htmlspecialchars($nrow[0]) . '</a> ';
                    }
                }

                /* Dependencies */
                $nquery = "SELECT library FROM $portsdb_name.dependencies WHERE portfile='" . mysql_real_escape_string($row['name']) .
                "' ORDER BY library";
                $nresult = mysql_query($nquery);
                if ($nresult && mysql_num_rows($nresult) > 0) {
                    print '<br /><i>Dependencies:</i> ';
                    while ($nrow = mysql_fetch_row($nresult)) {
                        $library = preg_replace('/^(?:[^:]*:){1,2}/', '', $nrow[0]);
                        print "<a href=\"$_SERVER[PHP_SELF]?by=library&amp;substr=" . urlencode($library) . '">'
                        . htmlspecialchars($library) . '</a> ';
                    }
                }

                /* Variants */
                $nquery = "SELECT variant FROM $portsdb_name.variants WHERE portfile='" . mysql_real_escape_string($row['name']) .
                "' ORDER BY variant";
                $nresult = mysql_query($nquery);
                if ($nresult && mysql_num_rows($nresult) > 0) {
                    print '<br /><i>Variants:</i> ';
                    while ($nrow = mysql_fetch_row($nresult)) {
                        print "<a href=\"$_SERVER[PHP_SELF]?by=variant&amp;substr=" . urlencode($nrow[0]) . '">'
                        . htmlspecialchars($nrow[0]) . '</a> ';
                    }
                }

                print '<br /><br /></dd>';
            } /* while (listing of matching ports) */
            print '</dl>';

            if ($paging) {
                print $pagecontrol;
            }

        /* When we hit this else, the query failed for some reason */
        } else {
            print '<p>An Error Occurred: '. mysql_error($portsdb_info['connection_handler']) . '</p>';
        }
        
    } /* if (main query sent to the DB) */
?>

</div>


<?php
    print_footer();
    mysql_close($portsdb_info['connection_handler']);
?>
