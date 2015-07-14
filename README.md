# PhotoOrganize

Command line task to organize media files into folders based on either a date in the filename, exif data or creation 
date. I made this to prepare organize the meda files that appear in my Seafile phone media repository to be integrated 
into my PhotoShow installation.

 - https://github.com/thibaud-rohmer/PhotoShow
 - https://www.seafile.com/en/home/

The other reason I made this script is to see what can be done with TaskPHP and Ardent Collections.

 - http://taskphp.github.io
 - https://github.com/morrisonlevi/Ardent
 
# Usage

Copy ``Makefile.dist`` to ``Makefile`` (``cp Makefile.dist Makefile``)
Edit the ``Makefile`` variables at the top of the file for your environment:

```
SRC=/import-folder-without-trailing-slash
DST=user@server/resync-destination-folder
```

It is important not to add a trailing slash (this is already done by the script for the ``rsync`` command).

```
SRC=/home/seafile/photos-phone/
DST=user@192.168.178.2:/photoshow/photos/Photos
```

Then run ``make import`` to import a directory with photo's. This will fill the ``TMP`` directory with folders matching
the dates found in the media files.

Finally run ``make transfer`` to send the files over to ``DST``.