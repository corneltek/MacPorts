<?

//
// File     : faq.php
// Version  : $Id: faq.php,v 1.5 2003/01/09 11:04:35 matt Exp $
// Location : /projects/darwinports/fr/faq.php
//

	include_once("$DOCUMENT_ROOT/includes/od_lib.inc.php");
	od_print_header("FAQ DarwinPorts", "fr", "iso-8859-1", "", 0, "/projects/darwinports");
?>

<center>
<table border="0" width="500" cellspacing="0" cellpadding="3">
<tr><td>

<h2>FAQ DarwinPorts</h2>


<p>
Ce document essaye de r�pondre aux question les plus fr�quemment pos�es � propos de DarwinPorts. 
</p>

<p><i>Auteur : Jordan K Hubbard</i></p>


<p><strong>Qu'EST-ce que DarwinPorts ?</strong></p>

<p>
Une description de DarwinPorts est plus compr�hensible en utilisant une comparaison : c'est une sorte de collection de ports comme ceux de <a href="http://www.freebsd.org/ports">FreeBSD</a> ou <a href="http://fink.sourceforge.net">fink</a> qui permet d'automatiser le processus de compilation et d'installation de logiciels tierce partie pour Mac OS X. Cela permet �galement de garder une trace des d�pendances requises pour un logiciel donn� et de savoir comment construire le logiciel souhait� sur Mac OS X et de l'installer dans un emplacement commun, ce qui revient � dire qu'un logiciel install� via DarwinPorts ne va pas aller se r�pandre dans tout le syst�me ou demander une connaissance particuli�re pour l'installer et surtout dans quel ordre il faut l'installer. 
</p>

<p><strong>Comment est impl�ment� DarwinPorts ?</strong></p>

<p>
Le syst�me DarwinPorts est pratiquement tout �crit en Tcl et a �t� pens� pour �tre int�gr� dans d'autres applications, comme par exemple dans une interface de navigation (comme ce projet en cours appel� <a href="http://www.opendarwin.org/cgi-bin/cvsweb.cgi/proj/dp-cocoa/">dp-cocoa</a>, une interface bas�e sur Cocoa) ou alors dans une application contr�l�e via une interface web. Il a �t� pr�vu pour �tre tr�s extensible depuis ses tous premiers d�buts, il est compos� de telle mani�re qu'un changement de conception et/ou d'infrastructure peut �tre op�r� ind�pendamment des ports, signifiant que si le syst�me �volue, les choses anciennes ne seront pas affect�es. 
</p>

<p>
M�me si DarwinPorts est �crit en Tcl, un utilisateur n'a pas besoin de conna�tre le langage Tcl pour pouvoir utiliser ce syst�me ou m�me ajouter de nouveaux ports. M�me si les fichiers de description des ports sont actuellement des programmes complets en Tcl, ils ressemblent plus � une liste comportant des paires de type cl�/valeur. 
</p>

<p><strong>Quelles sont les diff�rences de DarwinPorts compar� aux ports de FreeBSD ?</strong></p>

<p>
Les ports de FreeBSD sont essentiellement impl�ment�s comme de petites mais impressionnantes macros de BSD make(1) plac�es un peu partout et pouvant para�tre un peu opaques et non extensibles pour une personne voulant �tendre ou r�arranger des parties du syst�me. �tant donn� que les fichiers makefile ne sont pas la chose la plus facile � analyser, il est �galement plus difficile "d'extraire" la collection de ports de FreeBSD en donn�e pour une autre utilisation, comme g�n�rer la documentation des index ou des interfaces arbitraires pour la cr�ation ou la maintenance des ports. 
</p>

<p><strong>Pourquoi DarwinPorts a �t� enti�rement cr�� de A � Z plut�t que d'adopter quelque chose comme les ports FreeBSD ?</strong></p>

<p>
M�me en ne comptant pas les quelques limitations des ports FreeBSD d�crites ci-dessus, la "science" de cr�er des syst�mes de construction automatis�s est bien plus complexe � premi�re vue qu'il n'y para�t et il y aura toujours des approches nouvelles concernant la fa�on d'aborder le probl�me, ce que nous avons essay� de faire avec DarwinPorts. Il y a probablement d'autres syst�mes, certains d'entre eux ont d�j� �t� mentionn�s qui ont essay� � leurs mani�res de r�soudre ce probl�me et il y aura probablement beaucoup plus de syst�mes similaires essayant de trouver une solution unique qui plaise � tout le monde - �a n'est que plus ou moins impossible. Nous invitons toute personne susceptible de juger l'aspect de DarwinPorts d'apr�s ses propres sp�cificit�s et de le consid�rer comme un projet parall�le plut�t qu'un �lan de comp�tition car il y a plus d'un logiciel et autre syst�me qui sache g�rer et comment permettre l'automatisation de tout ceci. 
</p>

<p><strong>Quelles sont les conditions requises pour DarwinPorts ?</strong></p>

<p>
Il requiert actuellement Mac OS X 10.2 (Jaguar), avec les Developer Tools d'install�s puisque c'est le code de r�f�rence que la plupart d'entre nous utilise. Il y �galement un portage pr�vu pour permettre une compatibilit� avec la 10.1 (Puma) d�s que nous aurons pu identifier toutes les cons�quences des "variantes" qui ont besoin d'�tre ajout�es aux diff�rents ports. Le projet DarwinPorts prend des dispositions pour la version d'OS ou les "variantes" sp�cifiques d'architecture d'un port et nous souhaitons influencer ce m�canisme pour supporter plusieurs versions d'OS ainsi que plusieurs types d'architectures (pour Darwin/x86 par exemple) en une mani�re claire. 
</p>

<p><strong>Est-ce que DarwinPorts g�re �galement la gestion de paquet ?</strong></p>

<p>
Actuellement, DarwinPorts compile juste les logiciels depuis les sources, les installe et enregistre le processus d'installation afin de permettre � DarwinPorts d'�tre capable de les d�sinstaller si vous lui demandiez. Il cr�era �galement un binaire "instantan�" d'une installation d'un port que vous pourrez donner � quelqu'un d'autre afin de lui permettre d'�viter de repasser par les �tapes de compilation du port; mais la gestion de paquet est une chose que nous avons d�lib�r�ment mise de c�t� pour la "phase II" du projet, nous adopterons probablement alors un syst�me de gestion de paquet d�j� existant et feront que DarwinPorts g�n�re simplement ces paquets � la demande. m�me avec une "gestion propre et net des paquets", il sera toujours important de laisser le choix de compiler depuis les sources puisque quelque chose doit g�n�rer les paquets pour chaque version d'OS ou des diff�rents ports, et les d�veloppeurs qui modifient les librairies syst�me ou s'amusant � compiler un type de logiciel de diff�rentes mani�res peuvent trouver insuffisant pour leurs besoins un paquet du binaire d�j� "mis en bo�te" et pr�t � l'emploi.
</p>

<p><strong>Pourquoi est-ce que DarwinPorts installe tout dans /opt/local par d�faut ?</strong></p>

<p>
Premi�rement, cet emplacement peut �tre modifi� par un autre emplacement de votre choix, en �ditant /etc/ports/ports.conf donc rien n'est fig�. M�me l'infrastructure basique de DarwinPorts, qui s'installe dans /opt/local par d�faut peut �tre install�e n'importe ou simplement en modifiant la valeur de PREFIX en ligne de commande (reportez au fichier README.fr pour plus de d�tails). Deuxi�mement, nous avons s�lectionn� un CERTAIN emplacement pour que les choses � installer s'installent et qu'ils ne se heurtent pas � des composants ou des choses du syst�me d�j� install�s dans /usr/local, ainsi nous avons choisi de suivre l�chement la convention de Sun et de choisir finalement /opt/local. 
</p>

<p><strong>OK, donc comment commencer � jouer avec ?</strong></p>

<p>
Reportez-vous � la page web de <a href="http://www.opendarwin.org/projects/darwinports/fr/">DarwinPorts</a> pour des informations concernant le t�l�chargement du projet via CVS. Une fois une copie en votre possession, lisez le README.fr situ� au premier niveau de la hi�rarchie pour l'installation ainsi que les instructions basiques d'utilisation. 
</p>

<p><strong>Qu'elle est la commande pour voir les ports disponibles ?</strong></p>

<p>port search ".*"</p>
<p>
port search utilise une expression r�guli�re (regex) comme argument donc vous pouvez chercher un (ou des) port(s) particulier(s) qui vous int�resse(nt). 
</p>

<p><strong>Probl�mes connus et Incompatibilit�s</strong></p>

<p><i>Unable to open port: can't find package Pextlib 1.0</i></p>
<p>
DarwinPorts ne se compilera pas correctement avec les librairies TCL livr�es dans les premi�res versions du paquet TCL de Fink. Mettez � jour votre paquet TCL de Fink, ou assurez-vous que vous utilisez la librairie TCL du syst�me, et reconstruisez DarwinPorts. 
</p>

<p><i>Norton AntiVirus</i></p>
<p>
Le projet Fink a d�couvert r�cemment de nombreux probl�mes incluant des kernel panics et des gels durant la mise en place de patchs lorsque certains logiciels anti-virus �taient install�s. Vous devrez peut-�tre d�sactiver tout logiciel anti-virus avant d'utiliser DarwinPorts ou Fink.
</p>

</td></tr>
</table>
</center>


<? 
	od_print_footer("fr"); 
?>
