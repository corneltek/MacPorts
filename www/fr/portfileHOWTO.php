<?

//
// File     : portfileHOWTO.php
// Version  : $Id: portfileHOWTO.php,v 1.11 2003/04/17 03:35:58 matt Exp $
// Location : /projects/darwinports/portfileHOWTO.php
//

	include_once("$DOCUMENT_ROOT/includes/od_lib.inc.php");
	od_print_header("Comment �crire un Portfile pour DarwinPorts", "fr", "iso-8859-1", "", 0);
?>

<h2>
Comment �crire un Portfile pour DarwinPorts
</h2>
<pre><tt>
Kevin Van Vechten (<a href="mailto:kevin@opendarwin.org">kevin@opendarwin.org</a>), Felix Kronlage (<a href=mailto:fkr@opendarwin.org>fkr@opendarwin.org</a>)
15-Mar-2003
</tt></pre>
<h3>
Divers
</h3>
<p>
DarwinPorts automatise les t�ches usuelles requises pour le portage de logiciel sur Darwin. Les Portfiles contiennent les informations n�cessaires pour que la compilation et l'installation de logiciels particuliers soient faites correctement sous Darwin. Le but de DarwinPorts est de pouvoir garder la syntaxe des Portfiles aussi simple que possible, tout en supportant les cas sp�ciaux requis par la compilation et l'installation de beaucoup de logiciel afin que tout se passe avec succ�s.
</p>
<p>
Cet article d�crit la constitution d'un simple Portfile, et explore les fonctions les plus communes � DarwinPorts.
</p>
<h3>
Commencer
</h3>
<p>
Pour pouvoir travailler avec DarwinPorts, vous devrez le t�l�charger et l'installer sur votre syst�me. La <a href="http://opendarwin.org/projects/darwinports/fr/">page d'accueil</a> du projet DarwinPorts d�crit comment se le procurer et l'installer.
</p>
<p>
Comme vous vous int�ressez � l'�criture d'un Portfile, vous devriez invoquer la commande <tt>port</tt> avec l'option <tt>v</tt> (mode verbeux) et l'option <tt>d</tt> (mode d�boguage). Cela affichera des messages utile au d�boguage qui sont normalement omis lors de l'ex�cution de DarwinPorts. 
</p>
<p>
DarwinPorts effectuera plusieurs t�ches basiques pr�d�finies, qui sont :
</p>
<a name="basictoc"></a><h4>Sujets basiques</h4>
<ul>
<li><a href="#fetch">R�cup�ration des sources</a></li>
<li><a href="#checksum">V�rification du fichier t�l�charg�</a></li>
<li><a href="#extract">Extraction des sources dans un r�pertoire de travail</a></li>
<li><a href="#configure">Ex�cution d'un script Configure</a></li>
<li><a href="#build">Compilation des sources</a></li>
<li><a href="#install">Installation du programme dans le syst�me</a></li>
</ul>
<a name="advancedtoc"></a><h4>Sujets avanc�s</h4>
<ul>
<li><a href="#targets">Modifier des cibles</a></li>
<li><a href="#variants">Variantes du Portfile</a></li>
</ul>
<a name="appendixtoc"></a><h4>Annexe</h4>
<ul>
<li><a href="#portfilelist">Aper�u d'un Portfile</a></li>
</ul>

<h3>
<a name="fetch"></a>R�cup�ration des sources
</h3>
<p>
La premi�re chose � faire est de choisir un logiciel � porter. Pour cet exemple, nous allons utiliser ircII, un client IRC populaire. Nous commencerons avec un Portfile simple, d�crivant les attributs basiques d'ircII comme son nom, sa version et le site o� nous pouvons t�l�charger les sources. Cr�ez un r�pertoire de travail nomm� <tt>ircii</tt> et cr�ez � l'int�rieur un fichier nomm� <tt>Portfile</tt> ayant le contenu suivant :
</p>
<pre><tt>
# &#36;Id: &#36;
PortSystem 1.0
name               ircii
version            20020912
categories         irc
maintainers        kevin@opendarwin.org
description        an IRC and ICB client
long_description   The ircII program is a full screen, termcap based interface to Internet Relay \
                   Chat. It gives full access to all the normal IRC functions, plus a variety \
                   of additionnal options.
master_sites       ftp://ircftp.au.eterna.com.au/pub/ircII/
</tt></pre>
<p>
Un Portfile consiste en une suite de paires de type cl�/valeur. Chaque portfile doit commencer avec <tt># &#36;Id: &#36;</tt> qui est une balise RCS Id en commentaire. Apr�s la balise RCS Id, vient la d�claration <tt>PortSystem</tt>. Actuellement la seule d�claration valide est <tt>PortSystem 1.0</tt>. Les cl�s <tt>name</tt> et <tt>version</tt> d�crivent le nom et la version du logiciel. La cl� <tt>categories</tt> est une liste des cat�gories auquel le logiciel peut appartenir de fa�on logique; c'est utilis� dans un but d'organisation. La premi�re entr�e dans <tt>categories</tt> devrait correspondre au nom du r�pertoire o� doit r�sider le r�pertoire du port. La cl� <tt>maintainers</tt> devrait, elle, contenir votre adresse email. <tt>description</tt> affiche une br�ve description du port, alors que <tt>long_description</tt> affiche une compl�te description du port. La cl� <tt>master_sites</tt> devrait quant � elle contenir une liste des sites o� t�l�charger les sources. DarwinPorts utilise les termes "cl�s" et "options" indiff�remment comme la plupart des cl�s sont utilis�es comme des options d'une t�che particuli�re dans le processus du portage.
</p>
<p>
Arriv� � ce point, le Portfile est assez complet pour permettre le t�l�chargement d'ircII. Par d�faut, DarwinPorts ajoutera <tt>version</tt> � <tt>name</tt> et consid�rera que les sources sont au format <tt>.tar.gz</tt>. Depuis votre r�pertoire de travail, ex�cutez la commande suivante :
</p>
<pre><tt>
% port <tt>-d</tt> <tt>-v</tt> checksum
</tt></pre>
<p>
La commande <tt>port</tt> op�re directement sur le <tt>Portfile</tt> du r�pertoire de travail actuel. Vous devriez voir la m�me chose que ce qui suit :
</p>
<!--
.........|.........|.........|.........|.........|.........|.........|.........|
-->
<pre><tt>
DEBUG: Executing com.apple.main (ircii)
DEBUG: Executing com.apple.fetch (ircii)
--->  ircii-20020912.tar.gz doesn't seem to exist in /opt/local/var/db/dports/
distfiles
--->  Attempting to fetch ircii-20020912.tar.gz from ftp://
ircftp.au.eterna.com.au/pub/ircII/
DEBUG: Executing com.apple.checksum (ircii)
Error: No checksums statement in Portfile.  File checksums are:
ircii-20020912.tar.gz md5 2ae68c015698f58763a113e9bc6852cc
Error: Target error: com.apple.checksum returned: No checksums statement in 
Portfile.
</tt></pre>

<h3>
<a name="checksum"></a>V�rification du fichier t�l�charg�
</h3>
<p>
Remarquez que DarwinPorts v�rifiera dans un premier temps s'il existe une copie locale d'<tt>ircii-20020912.tar.gz</tt> mais il ne la trouvera pas, donc il la t�l�chargera depuis le site distant. La commande port ne se termine correctement car l'erreur : "No checksums statement in Portfile" est arriv�e. Les Portfiles doivent contenir une somme de contr�le md5 de tous les fichiers distribu�s -- cela permet � DarwinPorts de v�rifier l'exactitude et l'authenticit� des sources. Pour plus de souplesse, une somme de contr�le md5 pour les fichiers t�l�charg�s est affich�e lorsque l'argument <tt>checksums</tt> n'est pas sp�cifi�. Revenez en arri�re et ajoutez ce qui suit � votre Portfile :
</p>
<pre><tt>
checksums       md5 2ae68c015698f58763a113e9bc6852cc
</tt></pre>

<h3>
<a name="extract"></a>Extraction des sources dans un r�pertoire de travail
</h3>
<p>
Maintenant que nous avons une somme de contr�le, nous pouvons v�rifier nos sources. Proc�dons � l'extraction des sources dans notre r�pertoire de travail. Ex�cutez la commande suivante :
</p>
<pre><tt>
% port <tt>-d</tt> <tt>-v</tt> extract
</tt></pre>
<p>
Qui devrait afficher ce qui suit :
</p>
<!--
.........|.........|.........|.........|.........|.........|.........|.........|
-->
<pre><tt>
DEBUG: Skipping completed com.apple.main (ircii)
DEBUG: Skipping completed com.apple.fetch (ircii)
DEBUG: Executing com.apple.checksum (ircii)
--->  Checksum OK for ircii-20020912.tar.gz
DEBUG: Executing com.apple.extract (ircii)
--->  Extracting for ircii-20020912
--->  Extracting ircii-20020912.tar.gz ... DEBUG: Assembled command: 'cd /Users/
kevin/opendarwin/proj/darwinports/dports/irc/ircii/work &amp;&amp; gzip -dc /opt/local/
var/db/dports/distfiles/ircii-20020912.tar.gz | tar -xf -'
Done
</tt></pre>
<h3>
<a name="configure"></a>Ex�cution d'un script Configure
</h3>
<p>
Maintenant que les sources ont �t� extraites dans un r�pertoire nomm� <tt>work</tt> plac� dans le r�pertoire de travail actuel, nous pouvons configurer les sources afin de les compiler avec les options d�sir�es. Par d�faut, DarwinPorts assume que le logiciel que vous portez utilise un script configure autoconf, et toujours par d�faut, DarwinPorts passera l'argument <tt>--prefix=${prefix}</tt> au script configure, sp�cifiant que ce logiciel devra s'installer dans la hi�rarchie utilis�e par DarwinPorts.
</p>
<p>
Les options standards d'ircII semblent correctes pour une installation de base sur Darwin, donc inutile de lancer le <tt>configure</tt> manuellement, nous passerons directement � la phase de compilation.  
</p>

<h3>
<a name="build"></a>Compilation des sources
</h3>
<p>
Pour compiler, tapez ce qui suit :
</p>
<pre><tt>
% port build
</tt></pre>
<p>
Par d�faut, la phase de compilation ex�cute l'utilitaire syst�me make(1). (Cela peut �tre chang� avec l'option <tt>build.type</tt> qui accepte les arguments tel que <tt>bsd</tt>, <tt>gnu</tt> ou <tt>pbx</tt>. Alternativement, l'option <tt>build.cmd</tt> peut �tre utilis�e pour sp�cifier une commande de compilation arbitraire.) L'�tape ci-dessus a commenc� la compilation des sources, lorsqu'elle sera termin�e, nous serons fin pr�t pour installer le logiciel.  
</p>

<h3>
<a name="install"></a>Installation du programme dans le syst�me
</h3>
<p>
L'ancienne m�thode qui consistait � inclure la liste dans le fichier <tt>contents</tt> est devenue obsol�te en partie gr�ce au m�chanisme <tt>destroot</tt>. Avec <tt>destroot</tt> le logiciel est install� dans une hi�rarchie se situant dans le r�pertoire <tt>work</tt>. Alors que certains logiciels (comme ircII) ne requi�rent pas de manipulations sp�ciales pour �tre install�s dans le <tt>destroot</tt>, d'autres (comme <a href="http://www.opendarwin.org/cgi-bin/cvsweb.cgi/proj/darwinports/dports/net/ncftp/">ncftp</a>) ont besoin de la variable <tt>install.destroot</tt> afin qu'ils s'installent correctement dans le <tt>destroot</tt>.
</p>
<pre><tt>
�install.destroot � � � �mandir=${destroot}${prefix}/man prefix=${destroot}${prefix}
</tt></pre>
<p>
Regardez quelques-uns de nos ports pour voir plus d'exemples sur comment utiliser l'option <tt>install.destroot</tt>.
</p>
<p>
� pr�sent nous avons un portfile complet. Relancez l'�tape d'installation pour ajouter ce port � votre propre registre :
</p>
<pre><tt>
% sudo port <tt>-d</tt> <tt>-v</tt> install
</tt></pre>
Qui devrait afficher :
<pre><tt>
DEBUG: Skipping completed com.apple.main (ircii)
DEBUG: Skipping completed com.apple.fetch (ircii)
DEBUG: Skipping completed com.apple.checksum (ircii)
DEBUG: Skipping completed com.apple.extract (ircii)
DEBUG: Skipping completed com.apple.patch (ircii)
DEBUG: Skipping completed com.apple.configure (ircii)
DEBUG: Skipping completed com.apple.build (ircii)
DEBUG: Skipping completed com.apple.install (ircii)
DEBUG: Executing com.apple.registry (ircii)
--->  Adding ircii to registry, this may take a moment...
</tt></pre>

<h2>
Sujets avanc�s
</h2>

<h3>
<a name="targets"></a>Modifier des cibles
</h3>
<p>
Il est possible de modifier la fonctionnalit� d'une cible de compilation avec le code Tcl. Un exemple commun est le suivant, qui peut �tre utile pour un script sans script configure autoconf :
</p>
<pre><tt>
configure {}
</tt></pre>
<p>
Dans le Portfile, cela remplacera la fonctionnalit� de la cible de configure, aussi nous sauterons cette �tape. Il est �galement possible d'ex�cuter du code Tcl imm�diatement avant ou apr�s une cible standard. Cela peut �tre accompli de la mani�re suivante :
</p>
<!--
.........|.........|.........|.........|.........|.........|.........|.........|
-->
<pre><tt>
post-configure {
    reinplace "s|change.this.to.a.server|irc.openprojects.net|g" \
        "${workdir}/${worksrcdir}/config.h"
}
</tt></pre>
<p>
Cet exemple remplace l'occurrence de <tt>change.this.to.a.server</tt> avec <tt>irc.openprojects.net</tt> dans le fichier config.h qui a �t� g�n�r� pendant la phase pr�c�dant <tt>configure</tt>. Notez que c'est en quelque sorte un exemple invent� et voulu, car la m�me chose aurait pu �tre faite en sp�cifiant <tt>--with-default-server=irc.openprojects.net</tt> dans <tt>configure.args</tt>, mais l'approche est g�n�ralement utile lorsque de tels arguments ne sont pas pr�sents.
</p> 

<h3>
<a name="variants"></a>Variantes du Portfile
</h3>
<p>
Comme Darwin 6.0 g�re ipv6, il est possible de configurer le port avec l'option <tt>--with-ipv6</tt>. Cela peut �tre effectu� en ajoutant l'option suivante dans le Portfile :
</p>
<pre><tt>
configure.args      --disable-ipv6

variant ipv6 {
    configure.args-append  --enable-ipv6
}
</tt></pre>
<p>
Maintenant la compilation par d�faut n'inclura pas le support d'ipv6, mais si la variante ipv6 est voulue, ircII l'aura. Les options par elles-m�me devraient �tre consid�r�es comme un facteur d'assignation. Comme les variantes peuvent �tre utilis�es en combinaison avec d'autre, il est conseill� de les ajouter uniquement aux options au lieu de les �craser. Toutes les options peuvent avoir un suffixe avec <tt>-append</tt> ou <tt>-delete</tt> pour ajouter ou effacer un terme de la liste. Vous pouvez sp�cifier la compilation avec la variante ipv6 de la mani�re suivante :
</p>
<pre><tt>
% port build +ipv6
</tt></pre>

<h2>
Annexe
</h2>

<h3>
<a name="portfilelist"></a>Aper�u d'un Portfile
</h3>
<p>
Ce qui suit est le listage complet du Portfile d'ircII :
</p>
<pre><tt>
# &#36;Id: &#36;
PortSystem 1.0
name               ircii
version            20020912
categories         irc
maintainers        kevin@opendarwin.org
description        an IRC and ICB client
long_description   The ircII program is a full screen, termcap based interface to Internet Relay \
                   Chat. It gives full access to all the normal IRC functions, plus a variety \
                   of additionnal options.
homepage           http://www.eterna.com.au/ircii/
master_sites       ftp://ircftp.au.eterna.com.au/pub/ircII/
checksums          md5 2ae68c015698f58763a113e9bc6852cc
configure.args     --disable-ipv6

post-configure {
        reinplace "s|change.this.to.a.server|irc.openprojects.net|g" \
                  "${workdir}/${worksrcdir}/config.h"
}

variant ipv6 {
        configure.args-append --enable-ipv6
}
</tt></pre>

<? 
	od_print_footer("fr"); 
?>
