import ftplib
import base64
import os
import time 

def FtpRmTree(ftp, path):
    """Recursively delete a directory tree on a remote server."""
    wd = ftp.pwd()

    try:
        names = ftp.nlst(path)
    except ftplib.all_errors as e:
        # some FTP servers complain when you try and list non-existent paths
        print('FtpRmTree: Could not remove {0}: {1}'.format(path, e))
        return

    for name in names:
        if os.path.split(name)[1] in ('.', '..'): continue

        print('FtpRmTree: Checking {0}'.format(name))

        try:
            ftp.cwd(name)  # if we can cwd to it, it's a folder
            ftp.cwd(wd)  # don't try a nuke a folder we're in
            FtpRmTree(ftp, name)
        except ftplib.all_errors:
            ftp.delete(dirName+"/"+name)

    try:
        ftp.rmd(path)
    except ftplib.all_errors as e:
        print('FtpRmTree: Could not remove {0}: {1}'.format(path, e))
        
def upload(ftp, file):
  ext = os.path.splitext(file)[1]
  if ext in (".txt", ".htm", ".html"):
    ftp.storlines("STOR " + dirName+"/"+file, open(file))
  else:
    ftp.storbinary("STOR " + dirName+"/"+file, open(file, "rb"), 1024)


urlLogin="gerard64.ueuo.com"
dirName="teamGenerator"

ftp = ftplib.FTP(urlLogin)
ftp.login(urlLogin, base64.b64decode("RHJvZ2JhNjQ=".encode()).decode())

FtpRmTree(ftp,dirName)
# ftp.delete(dirName+"/"+"tmp.php")

ftp.mkd(dirName)
ftp.mkd(dirName+"/toto")
start_time = time.time()
upload(ftp, "tmp.php")
upload2(ftp, "tmp2.php")
interval = time.time() - start_time
print ('Total time in seconds:', interval)

# with open('tmp.php', 'r') as f:  
  # ftp.storlines('STOR %s' % 'remotefile.txt', f) 

ftp.quit()
