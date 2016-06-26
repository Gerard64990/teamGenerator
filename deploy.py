import ftplib
import os
import base64

server = "gerard64.ueuo.com"
username = "gerard64.ueuo.com"
password = base64.b64decode("RHJvZ2JhNjQ=".encode()).decode()


myFTP = ftplib.FTP(server, username, password)
myPath = os.path.abspath(os.path.dirname(__file__))+'\\'
# myDirName="teamGenerator"
#replace root by 983251

def uploadThis(path):
  files = os.listdir(path)
  os.chdir(path)
  for f in files:
    if os.path.isfile(path + r'\{}'.format(f)) and "tmp" not in f:
      print ("add file: " + path + f)
      fh = open(f, 'rb')
      myFTP.storbinary('STOR %s' % f, fh)
      fh.close()
    elif os.path.isdir(path + r'\{}'.format(f)) and "git" not in f and "tmp" not in f:
      print ("add dir: " + path + f)
      myFTP.mkd(f)
      myFTP.cwd(f)
      uploadThis(path + r'\{}'.format(f))
  myFTP.cwd('..')
  os.chdir('..')

# myFTP.mkd(dirName)
uploadThis(myPath) # now call the recursive function  