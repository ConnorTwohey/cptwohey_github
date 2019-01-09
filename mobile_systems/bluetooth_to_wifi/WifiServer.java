/*
* CSE 4340 Mobile Systems
* Connor Twohey
* 1001177282
* Project 2
* --------------------------------------------------------------
*
*/
import java.net.*;
import java.io.*;
import java.lang.Thread;

public class WifiServer{
  public static void main(String[] args) throws IOException, InterruptedException{
    int portNumber = 50005;
    String[] searchArgs = null;



    InetAddress ip = InetAddress.getLocalHost();
    //String localIP = "192.168.200.110";
    String localIP = ip.getHostAddress();
    searchArgs = new String[] {localIP};
    OBEXClient.main(searchArgs);

    try{
      System.out.println("About to make server socket");
      ServerSocket serverSocket = new ServerSocket(portNumber);
      System.out.println("Server Socket created");
      Socket server = serverSocket.accept();
      System.out.println("Received Connection");
      //OutputStream os = serverSocket.getOutputStream();
      InputStream is = server.getInputStream();

      File f = new File("hello.txt");
      while(true) {
        try{
          System.out.println("Beginning of while loop");
          byte[] buffer = new byte[1024];
          int len;
          System.out.println("Getting input socket");


          System.out.println("About to check buffer");
          if((len = is.read(buffer)) == 0) {
            System.out.println("Checking for changes");
            StringBuffer stringBufferBefore = readFile(f);
            System.out.println("Waiting for changes (60 seconds)");
            Thread.sleep(10 * 1000);
            StringBuffer stringBufferAfter = readFile(f);
            if(stringBufferBefore.toString().equals(stringBufferAfter.toString()) == true)
            System.out.println("File not changed");
            else {
              try {
                System.out.println("File changed");

                OutputStream os = server.getOutputStream();
                System.out.println("---------Sending\n");
                byte data[] = String.valueOf(stringBufferAfter).getBytes();
                os.write(data);
                os.flush();
                os.close();
              } catch (IOException e) {
                System.out.println("Error 1");
              }

            }
          }
          else {
            //is.read(buffer);
            System.out.println("Receiving");
            String s = new String(buffer, "UTF-8");
            StringBuffer buf = new StringBuffer(s);
            System.out.println("-----------Received\n");
            System.out.println("got: " + buf.toString());
            //File f = new File("hello.txt");
            //f.setWritable(true);
            BufferedWriter bwr = new BufferedWriter(new FileWriter(f));
            bwr.write(buf.toString());
            bwr.flush();
            bwr.close();
            System.out.println("Closing input stream");
            //is.close();
            System.out.println("Closed input stream");
          }

        }catch(IOException | InterruptedException e){
          System.out.println("Error 2");
        }
        System.out.println("End of while loop");
      }

    }catch(IOException e){
      System.out.println("Error 3");
    }
  }

  static StringBuffer readFile(File f) throws FileNotFoundException{
    FileReader fileReader = new FileReader(f);
    BufferedReader bufferedReader = new BufferedReader(fileReader);
    StringBuffer stringBuffer = new StringBuffer();
    String line;
    try{
      while((line = bufferedReader.readLine()) != null) {
        stringBuffer.append(line);
        stringBuffer.append("\n");
      }
    } catch(IOException e){
      System.out.println("OBEX Client readFile error");
    }
    return stringBuffer;
  }
}



/*
//Open file "hello.txt"
File f = new File("hello.txt");

//Send first instance of file
StringBuffer initialString = readFile(f);
byte[] initialFileData = String.valueOf(initialString).getBytes();
runPut(clientSession, initialFileData);

//Check file for changes every 60 sec and propagate changes to Server
while(true) {
StringBuffer stringBufferBefore = readFile(f);
System.out.println("Waiting for changes (60 seconds)");
Thread.sleep(10 * 1000);
StringBuffer stringBufferAfter = readFile(f);

//If bufferBefore is equal to bufferAfter then file has not changed,
//else file has changed and so send changes
if(stringBufferBefore.toString().equals(stringBufferAfter.toString()) == true)
System.out.println("File not changed");
else {
System.out.println("File changed");
byte[] data = String.valueOf(stringBufferAfter).getBytes();
hs.setHeader(HeaderSet.NAME, "hello.txt");
hs.setHeader(HeaderSet.TYPE, "text");
runPut(clientSession, hs, data);
}
*/
