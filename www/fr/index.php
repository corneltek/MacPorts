<?

//
// File     : index.php
// Version  : $Id: index.php,v 1.11 2003/05/05 17:20:15 landonf Exp $
// Location : /fr/projects/darwinports/index.php
//

	include_once("$DOCUMENT_ROOT/includes/od_lib.inc.php");
	od_print_header("DarwinPorts", "fr", "iso-8859-1", "", 0, "/projects/darwinports");
?>

<center>
<table border="0" width="500" cellspacing="0" cellpadding="3">
<tr><td>

<h2>Projects :: DarwinPorts</h2>

<p>
<i>Chefs de projet : </i>
<a href="mailto:landonf@opendarwin.org">Landon Fuller</a>, 
<a href="mailto:jkh@opendarwin.org">Jordan Hubbard</a>,
<a href="mailto:kevin@opendarwin.org">Kevin Van Vechten</a>
</p>

<p>
Le projet DarwinPorts a pour ambition de fournir un nombre important de logiciels port�s, simples � installer et disponibles librement pour le syst�me Darwin. Reportez-vous � la <a href="http://www.opendarwin.org/projects/darwinports/fr/faq.php">FAQ</a> pour plus d'information s'il vous pla�t. Pour un tutorial sur comment cr�er un Portfile, reportez-vous au <a href="http://www.opendarwin.org/projects/darwinports/fr/portfileHOWTO.php">Portfile HOWTO</a>.  
</p>
<p>
Le <a href="http://darwinports.gene-hacker.net/docs/guide/">guide d'utilisation DarwinPorts</a> est une excellente r�f�rence sur les concepts ainsi que la syntaxe de DarwinPorts. (Notez que ce travail est en cours de progression, donc n'h�sitez pas � nous faire parvenir vos suggestions et rapports de bogues via le module doc de DarwinPorts sur <a href="http://www.opendarwin.org/bugzilla/">Bugzilla</a>.)
</p>

<p><strong>Statut du Projet</strong></p>

<p>
Nombre de ports ont d�j� �t� cr��s et rendent le syst�me raisonnablement utilisable, comme une <i>BETA</i> pour quiconque souhaite s'y int�resser. Vous pouvez d'ores et d�j� trouver une liste de <a href="http://www.opendarwin.org/projects/darwinports/fr/ports.php">logiciels disponibles</a> ici.
</p>
<p>Une GUI fonctionnelle bas�e sur Cocoa pour DarwinPorts, appel�e <a href="http://www.opendarwin.org/cgi-bin/cvsweb.cgi/proj/darwinports/dports/sysutils/portmanager/">Ports Manager.app</a>, est disponible et son d�veloppement est actuellement actif. En voici une <a href="http://www.opendarwin.org/~fkr/portsmanager.png">capture d'�cran</a>.
</p>
<p>
Les suggestions, requ�tes ou rapports de bogues devront �tre soumis � <a href="http://www.opendarwin.org/bugzilla/">Bugzilla</a>.
</p>

<p><strong>R�cup�rer le projet depuis CVS</strong></p>

<p>
Utilisez les commandes suivantes pour r�cup�rer le projet depuis le serveur CVS d'OpenDarwin (requis pour Ports Manager.app) :
</p>

<p>
<pre>
cvs -d :pserver:anonymous@anoncvs.opendarwin.org:/Volumes/src/cvs/od login
cvs -d :pserver:anonymous@anoncvs.opendarwin.org:/Volumes/src/cvs/od co -P darwinports
</pre>

<p>
Utilisez les commandes suivantes pour r�cup�rer Ports Manager.app depuis le serveur CVS d'OpenDarwin :
</p>

<p>
<pre>
cvs -d :pserver:anonymous@anoncvs.opendarwin.org:/Volumes/src/cvs/od login
cvs -d :pserver:anonymous@anoncvs.opendarwin.org:/Volumes/src/cvs/od co -P PortsManager
</pre>

Lorsque le serveur vous demande un mot de passe, appuyez simplement sur retour; vu qu'il n'y pas de mot de passe. Le d�p�t du CVS peut �tre examin� via <a href="http://www.opendarwin.org/cgi-bin/cvsweb.cgi/proj/darwinports/">CVSweb</a>.
</p>

<p>
Afin d'installer et d'utiliser DarwinPorts, les <a href="http://developer.apple.com/tools">outils d�veloppeurs</a> de Mac OS X.
</p>

<p><strong>Listes de diffusion et canaux IRC du projet</strong></p>

<p>La <a
href="http://www.opendarwin.org/mailman/listinfo/darwinports">liste de diffusion darwinports</a> est ouverte � tout le monde et c'est l'endroit o� les discussions sur l'architecture et les fonctionnalit�s du projet sont tenues. Ceux souhaitant voir les historiques CVS concernant les soumissions d�taillant changement par changement la progression du projet peuvent �galement s'inscrire sur la liste de diffusion <a href="http://www.opendarwin.org/mailman/listinfo/cvs-darwinports-all">cvs-darwinports-all</a>.
</p>

<p>
Pour les discussions en temps r�el, elles se tiennent g�n�ralement sur le canal #opendarwin disponible via le r�seau <a href="http://freenode.net/">Open Projects</a>.
</p>

<p><strong>Se joindre au projet</strong></p>

<p>
Nous recherchons des personnes qui voudrait nous aider � faire de ce projet un succ�s. Il y a encore de nombreuses parties qui demandent du travail, incluant : la documentation, la cr�ation de ports, la maintenance des ports et les tests.
</p>

<p>
Toute contribution est tr�s appr�ci�e et la bienvenue ! Si vous �tes int�ress�, envoyez soit un e-mail � un des chefs du projet en demandant qu'est-ce qui a besoin d'�tre fait ou bien simplement en soumettant une application pour rejoindre le projet via ce <a href="/en/joinproject.php">formulaire</a>. Merci!
</p>

</td></tr>
</table>
</center>


<? 
	od_print_footer("fr"); 
?>
