<?
	require_once("$DOCUMENT_ROOT/en/includes/od_lib.inc.php");
	require_once("$DOCUMENT_ROOT/includes/od_db.inc.php");

	od_print_header("DarwinPorts Portfiles", "en", "iso-8859-1", "", 0);
?>
	<center>
	<h1>Portfiles DarwinPorts</h1>
	</center>

	<p>
	Cette page vous permet de rechercher dans l'index actuel des ports DarwinPorts. <br />
	<i>Derni�re mise-�-jour de l'index : </i>
	<?
		$sql = "SELECT UNIX_TIMESTAMP(activity_time) FROM darwinports.log ORDER BY UNIX_TIMESTAMP(activity_time) DESC";
		$result = mysql_query($sql);
		if($result && $row = mysql_fetch_row($result)) {
				echo date("d-M-Y H:i:s", $row[0]);
		}
	?>
	</p>
	
	<form action="<?= $PHP_SELF; ?>">
	<table>
		<tr>
			<th>Rechercher par :</th>
			<td>
				<select name="by">
				<option value="name"<? if ($by == "name") { echo " selected=\"selected\""; } ?>>Nom du logiciel</option>
				<option value="desc"<? if ($by == "desc") { echo " selected=\"selected\""; } ?>>Description</option>
				<option value="cat"<? if ($by == "cateogry") { echo " selected=\"selected\""; } ?>>Cat�gorie</option>
				<option value="maintainer"<? if ($by == "maintainer") { echo " selected=\"selected\""; } ?>>Mainteneur</option>
				</select>
			</td>
			<td><input type="text" name="substr" size="40" /></td>
			<td><input type="submit" name="Search" /></td>
		</tr>
		<tr><td colspan="4"><hr size="1" noshade="noshade" /></td></tr>
		<tr>
<?
		$result = mysql_query("SELECT count(*) from darwinports.portfiles");
		if ($result) {
			$row = mysql_fetch_array($result);
			$count = $row[0];
		} else {
			$count = 0;
		}
?>
			<td colspan="4" align="left"><a href="<?= $PHP_SELF; ?>?by=all">Voir tous les ports (<?= $count; ?>)</a></td>
		</tr>
		<?
			if (!$by || (!$substr && $by != "all")) {
		?>
		<tr><td colspan="4"><hr size="1" noshade="noshade" /></td></tr>
		<tr><th colspan="4" align="left">Voir par cat�gorie :</th></tr>
		<?
				$query = "SELECT DISTINCT category FROM darwinports.categories ORDER BY category";
				$result = mysql_query($query);
				if($result) {
					while( $row = mysql_fetch_assoc($result) ) {
		?>
		<tr><td colspan="4"><a href="<?= $PHP_SELF; ?>?by=cat&substr=<?= $row['category']; ?>"><?= $row['category']; ?></a></td></tr>
		<?
					}
				}
			}
		?>
	</table>
	</form>

<?
	if ($by && ($substr || $by == "all")) {
		$fields = "name, path, version, description";
		$query = "1";
		$tables = "darwinports.portfiles p";
		if ($by == "name") {
			$query = $query . " AND p.name LIKE '%" . addslashes($substr) . "%'";
		}
		if ($by == "desc") {
			$query = $query . " AND p.description LIKE '%" . addslashes($substr) . "%'";
		}
		if ($by == "cat") {
			$tables = $tables . ", darwinports.categories c";
			$query = $query . " AND c.portfile=p.name AND c.category='" . addslashes($substr) . "'";
		}
		if ($by == "variant") {
			$tables = $tables . ", darwinports.variants v";
			$query = $query . " AND v.portfile=p.name AND v.variant='" . addslashes($substr) . "'";
		}
		if ($by == "platform") {
			$tables = $tables . ", darwinports.platforms pl";
			$query = $query . " AND pl.portfile=p.name AND pl.platform ='" . addslashes($substr) . "'";
		}
		if ($by == "maintainer") {
			$tables = $tables . ", darwinports.maintainers m";
			$query = $query . " AND m.portfile=p.name AND m.maintainer LIKE '%" . addslashes($substr) . "%'";
		}
		$query = "SELECT DISTINCT $fields FROM $tables WHERE $query ORDER BY name";
		$result = mysql_query($query);
		if($result) {
?>
	<p>
	<i><?= mysql_num_rows($result); ?> Portfile<? if (mysql_num_rows($result) != 1) { echo "s"; } ?> s�lectionn�(s)</i>
	</p>
	<dl>
<?		
			while( $row = mysql_fetch_assoc($result) ) {
?>
	<dt><b><a href="http://www.opendarwin.org/projects/darwinports/darwinports/dports/<?= $row['path']; ?>/Portfile"><?= $row['name']; ?></a></b> <?= $row['version']; ?></dt>
	<dd>
	<?= $row['description']; ?><br />
	<?
// MAINTAINERS
				$nquery = "SELECT maintainer FROM darwinports.maintainers WHERE portfile='" . $row['name'] . "' ORDER BY is_primary DESC, maintainer";
				$nresult = mysql_query($nquery);
				if ($nresult) {
?>
	<i>Maintainu par :</i>
<?
					$primary = 1;
					while ( $nrow = mysql_fetch_array($nresult) ) {
						if ($primary) { echo "<b>"; }
					?>
						<a href="mailto:<?= $nrow[0]; ?>"><?= $nrow[0]; ?></a>
					<?
						if ($primary) { echo "</b>"; }
						$primary = 0;
					}
				}

// CATEGORIES
				$nquery = "SELECT category FROM darwinports.categories WHERE portfile='" . $row['name'] . "' ORDER BY is_primary DESC, category";
				$nresult = mysql_query($nquery);
				if ($nresult) {
?>
	<br />
	<i>Cat�gorie(s) :</i>
<?
					$primary = 1;
					while ( $nrow = mysql_fetch_assoc($nresult) ) {
						if ($primary) { echo "<b>"; }
					?>
						<a href="<?= $PHP_SELF; ?>?by=cat&substr=<?= $nrow['category']; ?>"><?= $nrow['category']; ?></a>
					<?
						if ($primary) { echo "</b>"; }
						$primary = 0;
					}
				}

// PLATFORMS
				$nquery = "SELECT platform FROM darwinports.platforms WHERE portfile='" . $row['name'] . "' ORDER BY platform";
				$nresult = mysql_query($nquery);
				if ($nresult && mysql_num_rows($nresult) > 0) {
?>
	<br />
	<i>Plateforme :</i>
<?
					while ( $nrow = mysql_fetch_array($nresult) ) {
						$platform = $nrow[0];
					?>
						<a href="<?= $PHP_SELF; ?>?by=platform&substr=<?= $platform; ?>"><?= $platform; ?></a>
					<?
					}
				}

// DEPENDENCIES
				$nquery = "SELECT library FROM darwinports.dependencies WHERE portfile='" . $row['name'] . "' ORDER BY library";
				$nresult = mysql_query($nquery);
				if ($nresult && mysql_num_rows($nresult) > 0) {
?>
	<br />
	<i>D�pendance(s) :</i>
<?
					while ( $nrow = mysql_fetch_array($nresult) ) {
						// lib:libpng.3:libpng -> libpng
						$library = eregi_replace("^[^:]*:[^:]*:", "", $nrow[0]);
					?>
						<a href="<?= $PHP_SELF; ?>?by=name&substr=<?= $library; ?>"><?= $library; ?></a>
					<?
					}
				}
/*
// VARIANTS
				$nquery = "SELECT variant FROM darwinports.variants WHERE portfile='" . $row['name'] . "' ORDER BY variant";
				$nresult = mysql_query($nquery);
				if ($nresult && mysql_num_rows($nresult) > 0) {
?>
	<br />
	<i>Variantes :</i>
<?
					while ( $nrow = mysql_fetch_array($nresult) ) {
						$variant = $nrow[0];
					?>
						<a href="<?= $PHP_SELF; ?>?by=variant&substr=<?= $variant; ?>"><?= $variant; ?></a>
					<?
					}
				}
*/
	?>
	<br />
	</dd>
	<br />
<?	
			} 
		} else {
			echo "An Error Occurred. (501)";
		}
	}
?>
	</dl>
<?
	od_print_footer("en"); 
?>
