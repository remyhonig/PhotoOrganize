# PhotoOrganize

Command line task to organize media files into folders based on either a date in the filename, exif data or creation 
date. I made this to prepare organize the meda files that appear in my Seafile phone media repository to be integrated 
into my PhotoShow installation.

 - https://github.com/thibaud-rohmer/PhotoShow
 - https://www.seafile.com/en/home/

The other reason I made this script is to see what can be done with TaskPHP and Ardent Collections.

 - http://taskphp.github.io
 - https://github.com/morrisonlevi/Ardent
 
# Installation

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

If you do not have composer installed then first run ``make install`` to download all dependencies of this script.

# Usage

Then run ``make import`` to import a directory with photo's. This will fill the ``TMP`` directory with folders matching
the dates found in the media files and symlinks to the original files. It will not alter the original files in any way.

Finally run ``make transfer`` to send the files over to ``DST``. Files will not be overwritten.

# Screenshots

## Import

The import command will show a preview of how the files will be organized.

```
> make import
...
/tmp/photoorganize-2015-07-14/2014/07/24 (15 files)
    IMG_20140724_092321759.jpg
    IMG_20140724_092323932.jpg
/tmp/photoorganize-2015-07-14/2014/02/28 (2 files)
    VID_20140228_101026789.mp4
    VID_20140228_101042830.mp4
/tmp/photoorganize-2015-07-14/2014/03/28 (1 files)
    VID_20140328_161151040.mp4
/tmp/photoorganize-2015-07-14/2014/06/21 (1 files)
    VID_20140621_181709840.mp4
/tmp/photoorganize-2015-07-14/2014/07/15 (1 files)
    VID_20140715_212205133.mp4
======================================================================
Files will be read from      : /home/user/seafile/phone-photos
Symlinks will be stored in   : /tmp/photoorganize-2015-07-14
Files will be transferred to : user@server:/data/photoshow/photos/Photos
======================================================================
Press [ENTER] now to create symlinks...
Running symlink...
created /tmp/photoorganize-2015-07-14/2014/02/27
created /tmp/photoorganize-2015-07-14/2014/02/28
created /tmp/photoorganize-2015-07-14/2014/03/02
...
created 99 directories in total
opening connection using ssh -l user server rsync --server -vvnlogDtpr --ignore-existing . /data/photoshow/photos/Photos 
building file list ... 
1358 files to consider
delta-transmission enabled
./
2011/08/30/IMG_5103.JPG exists
2013/12/02/IMG_4531.JPG exists
2013/12/02/IMG_4545.JPG exists
....
total: matches=0  hash_hits=0  false_alarms=0 data=0

sent 48611 bytes  received 12746 bytes  40904.67 bytes/sec
total size is 4885937179  speedup is 79631.29
```

## Transfer

The transfer command will ask for confirmation before doing anything.

```
make transfer
======================================================================
Files will be read from      : /home/user/seafile/phone-photos
Symlinks will be stored in   : /tmp/photoorganize-2015-07-14
Files will be transferred to : user@server:/data/photoshow/photos/Photos
======================================================================
Press [ENTER] now to actually transfer files with rsync...
```