<?

//
// File     : variants.php
// Version  : $Id: variants.php,v 1.2 2003/01/12 00:25:32 matt Exp $
// Location : /projects/darwinports/variants.php
//

        include_once("$DOCUMENT_ROOT/includes/od_lib.inc.php");
        od_print_header("Variantes des Portfile DarwinPorts", "fr", "iso-8859-1", "", 0);
?>

<h2>
Variantes
</h2>
<pre><tt>
Kevin Van Vechten | <a href="mailto:kevin@opendarwin.org">kevin@opendarwin.org</a>
9-Oct-2002
</tt></pre>
<a name="basictoc"></a><h4>Variantes</h4>
<ul>
<li><a href="#what_are_they">Qu'est-ce que les variantes ?</a></li>
<li><a href="#additive_variants">Les variantes peuvent s'additionner</a></li>
<li><a href="#ordering_variants">Agencer l'ordre des variantes</a></li>
<!-- <li><a href="#conflicting_variants">Conflicting Variants</a></li> -->
<li><a href="#default_variants">Variantes par d�faut</a></li>
<li><a href="#implicit_variants">Variantes implicites</a></li>
</ul>
<h3>
<a name="what_are_they"></a>Qu'est-ce que les variantes ?
</h3>
<p>
Beaucoup de logiciels ont des fonctionnalit�s optionnelles qui ont besoin d'�tre d�finies au moment de la compilation. Ces logiciels sont soit compil�s avec ou sans le support de certaines fonctions. Un des exemples les plus flagrant est de compiler un logiciel avec ou sans le support d'X11.
</p>
<p>
Les variantes furent cr�er pour pallier � ce besoin d'avoir ou non ces fonctionnalit�s suppl�mentaires. Souvent, compiler avec ou sans le support d'X11 est juste l'affaire de sp�cifier <tt>--with-x11</tt> ou <tt>--without-11</tt> dans la variable <tt>configure.args</tt>. En gros, une variante est l'�quivalent de la d�claration <tt>if</tt> en Tcl. Donc si la variante a �t� sp�cifi�e par l'utilisateur, le bloc de code est ex�cut�. Voici un exemple d'une variante pour X11 :
</p>
<pre><tt>
configure.args --without-x11

variant x11 {
    configure.args-delete --without-x11
    configure.args-append --with-x11
}
</tt></pre>
<p>
Normalement, le code se situant en dehors du bloc d�di� � la variante est ex�cut� sans r�serve avant que toute variante soit prise en compte. Cepdendant, si <tt>+x11</tt> a �t� sp�cifi�e, les d�clarations <tt>configure.args-delete</tt> et <tt>configure.args-append</tt> seront �galement prises en compte.
</p>
<h3>
<a name="additive_variants"></a>Les variantes peuvent s'additionner
</h3>
<p>
Si deux variantes sont d�finies, par exemple <tt>x11</tt> et <tt>kde</tt>, une des deux ou carr�ment les deux peuvent �tre choisies et utilis�es par l'utilisateur. Sp�cifier <tt>+x11</tt> aura pour effet de prendre en compte la variante <tt>x11</tt>, et sp�cifier <tt>+kde</tt> aura pour effet la prise en compte de la variante <tt>kde</tt>. Si <tt>+x11 +kde</tt> est sp�cifi�e, les deux variantes seront prises en compte. Il est �galement possible de d�finir des variantes contenant plusieurs termes. Par exemple :
</p>
<pre><tt>
variant x11 kde {
    # do something here, when both x11 and kde are selected
}
</tt></pre>
<p>
Dans cet exemple, la variante <tt>x11-kde</tt> sera ex�cut�e uniquement si <tt>x11</tt> et <tt>+kde</tt> sont sp�cifi�es. Cela permet aux conflits entre deux variantes "primitives" d'�tre r�solues sans beaucoup d'efforts. Aussi, parce que ces variantes combin�es sont le plus souvent utilis�es pour r�soudre des conflits, elles sont prises en compte apr�s que chaque parties constituantes aient �t� prises en compte.
</p>
<h3>
<a name="ordering_variants"></a>Agencer l'ordre des variantes
</h3>
<p>
Parfois il est utile de garantir qu'une variante soit lanc�e apr�s une autre variante. Cela peut rendre la manipulation des variables plus franche, plus souple, valable �galement pour d'autres op�rations. L'agencement peut se faire gr�ce au mot-cl� <tt>follows</tt>. (<b><i>Important :</i></b> le mot-cl� <tt>follows</tt> n'a pas encore �t� impl�ment�.) Dans l'exemple suivant, la variante <tt>kde</tt> d�clare que m�me si <tt>+kde</tt> et <tt>+x11</tt> sont sp�cifi�es, la variante <tt>kde</tt> sera toujours prise en compte apr�s la variante <tt>x11</tt>. La variante <tt>kde</tt> restera prise en compte m�me si seulement <tt>+kde</tt> est sp�cifi�e.
<pre><tt>
variant kde follows x11 {
    # do something here, after x11
}
</tt></pre>
<p>
D'un autre c�t�, il est possible d'autoriser <tt>kde</tt> a �tre une variante si et uniquement si <tt>x11</tt> a �galement �t� s�lectionn�e. Cela peut �tre fait en utilisant le mot-cl� <tt>requires</tt>, utilis� de la mani�re suivante :
</p>
<pre><tt>
variant kde requires x11 {
    # do something here, only after x11
}
</tt></pre>
<!--
<h3>
<a name="conflicting_variants"></a>Conflicting Variants
</h3>
<p>
</p>
-->
<h3>
<a name="default_variants"></a>Variantes par d�faut
</h3>
<p>
Un Portfile peut contenir un set par d�faut de variantes, qui sera s�lectionn� lors de la prise en charge du Portfile, � moins que l'utilisateur ne les ait explicitement refus�s. Cela peut �tre fait en utilisant la commande <tt>default_variants</tt> dans le Portfile, comme ci-dessous :
</p>
<pre><tt>
default_variants    +x11 +kde
</tt></pre>
<p>
Cela indique que les variantes <tt>x11</tt> et <tt>kde</tt> seront prises en compte � moins que l'utilisateur n'ait sp�cifi� <tt>-kde -x11</tt> � la ligne de commande.
</p>
<h3>
<a name="implicit_variants"></a>Variantes implicites
</h3>
<p>
Quelques variantes sont implicitement sp�cifi�es par le syst�me. Comme les variantes par d�faut, celles-ci peuvent �tre annul�es � la ligne de commande. Actuellement la plateforme et l'architecture sont deux variantes implicites. Les variantes du m�me nom que les valeurs de <tt>os.platform</tt> et <tt>os.arch</tt> sont s�lectionn�es. <tt>os.platform</tt> contient la valeur d'<tt>uname -s</tt> comme <tt>darwin</tt> ou <tt>freebsd</tt>. <tt>os.arch</tt> contient la valeur d'<tt>uname -p</tt> comme <tt>ppc</tt> ou <tt>i386</tt>.
</p>

<?
        od_print_footer("fr");
?>
