/*
* CSE 4340 Mobile Systems
* Connor Twohey
* 1001177282
* Project 1
* --------------------------------------------------------------
* Majority of code from bluecove overview examples
* http://www.bluecove.org/bluecove/apidocs/overview-summary.html
*/


import javax.microedition.io.Connector;
import javax.obex.*;
import java.lang.String;
import java.util.Scanner;
import java.lang.Thread;
import java.io.*;

public class OBEXClient {

  public static void main(String[] args) throws IOException, InterruptedException {

    System.out.println("\n-------------STARTING-------------\n");

    String serverURL = null; // = "btgoep://0019639C4007:6";
    if ((args != null) && (args.length > 0)) {
      serverURL = args[0];
    }
    if (serverURL == null) {
      String[] searchArgs = null;
      // Connect to OBEXPutServer from examples
      searchArgs = new String[] { "11111111111111111111111111111123" };
      ServicesSearch.main(searchArgs);
      if (ServicesSearch.serviceFound.size() == 0) {
        System.out.println("OBEX service not found");
        System.out.println("\n---------------DONE---------------\n");
        return;
      }
      // Select the first service found
      serverURL = (String)ServicesSearch.serviceFound.elementAt(0);
    }

    System.out.println("Connecting to " + serverURL);

    ClientSession clientSession = (ClientSession) Connector.open(serverURL);
    HeaderSet hsConnectReply = clientSession.connect(null);
    if (hsConnectReply.getResponseCode() != ResponseCodes.OBEX_HTTP_OK) {
      System.out.println("Failed to connect");
      return;
    }

    File f = new File("hello.txt");
    f.setWritable(true, false);
    StringBuffer initialString = readFile(f);
    byte[] initialFileData = String.valueOf(initialString).getBytes();
    runPut(clientSession, initialFileData);

    while(true) {
      StringBuffer stringBufferBefore = readFile(f);
      System.out.println("Waiting for changes (60 seconds)");
      Thread.sleep(60 * 1000);
      StringBuffer stringBufferAfter = readFile(f);
      if(stringBufferBefore.toString().equals(stringBufferAfter.toString()) == true)
      System.out.println("File not changed");
      else {
        System.out.println("File changed");
        byte[] data = String.valueOf(stringBufferAfter).getBytes();
        runPut(clientSession, data);

      }
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

  static void runPut(ClientSession con,byte[] data) throws IOException{
    HeaderSet hs = con.createHeaderSet();
    hs.setHeader(HeaderSet.NAME, "hello.txt");
    hs.setHeader(HeaderSet.TYPE, "text");
    hs.setHeader(HeaderSet.LENGTH, new Long(data.length));

    Operation putOperation = con.put(hs);

    DataOutputStream os = putOperation.openDataOutputStream();

    os.write(data);
    os.close();
    putOperation.close();
  }

  static void runGet() throws IOException{

  }
}
