--- ltmain.sh.orig	Sat Nov  9 05:40:11 2002
+++ ltmain.sh	Mon Apr  7 02:00:39 2003
@@ -4047,10 +4047,10 @@
 
 # Directory that this library needs to be installed in:
 libdir='$install_libdir'"
-	  if test "$installed" = no && test $need_relink = yes; then
-	    $echo >> $output "\
-relink_command=\"$relink_command\""
-	  fi
+#	  if test "$installed" = no && test $need_relink = yes; then
+#	    $echo >> $output "\
+#relink_command=\"$relink_command\""
+#	  fi
 	done
       fi
