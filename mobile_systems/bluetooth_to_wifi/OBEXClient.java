/*
* CSE 4340 Mobile Systems
* Connor Twohey
* 1001177282
* Project 2
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
import java.net.*;

public class OBEXClient {

  public static void main(String[] args) throws IOException, InterruptedException {

    System.out.println("\n-------------STARTING-------------\n");

    String serverURL = null; // = "btgoep://0019639C4007:6";
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

    //Create clientSession with connection serverURL
    ClientSession clientSession = (ClientSession) Connector.open(serverURL);
    HeaderSet hsConnectReply = clientSession.connect(null);
    if (hsConnectReply.getResponseCode() != ResponseCodes.OBEX_HTTP_OK) {
      System.out.println("Failed to connect");
      return;
    }

    //Create initial headerset
    HeaderSet hs = clientSession.createHeaderSet();
    hs.setHeader(HeaderSet.NAME, "");
    hs.setHeader(HeaderSet.TYPE, "");

    //Send IP address to server
    String ip = args[0];
    System.out.println("IP: " + ip);

    byte[] data = String.valueOf(ip).getBytes();
    runPut(clientSession, hs, data);

    /*
    try{
      ServerSocket serverSocket = new ServerSocket(portNumber);
      Socket clientSocket = serverSocket.accept();
    }catch(IOException e){

    }*/

    //hs.setHeader(HeaderSet.NAME, "");
    //hs.setHeader(HeaderSet.TYPE, "");
    //runPut(clientSession, hs, data);
  }
  /*
  * readFile()
  * @args File f
  * @returns StringBuffer
  * This function reads file f and stores each line of data into a
  * stringBuffer and returns that buffer.
  */
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
  /*
  * runPut()
  * @args ClientSession con, HeaderSet hs, byte[] data
  * @returns void
  * This function takes in ClientSession con which is connected to some serverURL,
  * HeaderSet hs, and byte array data. It opens an output stream and send the byte array
  * to the server.
  */
  static void runPut(ClientSession con, HeaderSet hs, byte[] data) throws IOException{
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
