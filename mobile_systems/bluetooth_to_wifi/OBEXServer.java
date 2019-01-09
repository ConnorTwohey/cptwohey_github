/*
* CSE 4340 Mobile Systems
* Connor Twohey
* 1001177282
* Project 2
* --------------------------------------------------------------
* Majority of code from bluecove overview examples
* http://www.bluecove.org/bluecove/apidocs/overview-summary.html
*/


import java.io.*;
import javax.bluetooth.*;
import javax.microedition.io.Connector;
import javax.microedition.io.Connection;
import javax.obex.*;
import java.lang.Thread;

public class OBEXServer {

  //Used to identify server
  static final String serverUUID = "11111111111111111111111111111123";
  //Used to send received ip address back to WifiClient
  static String ip = new String();

  public static String main(String[] args) throws IOException {
    //Set device as discoverable
    LocalDevice.getLocalDevice().setDiscoverable(DiscoveryAgent.GIAC);
    //Open connection to local device
    SessionNotifier serverConnection = (SessionNotifier) Connector.open("btgoep://localhost:"+ serverUUID + ";name=ObexExample");

    //handle connection requests
    int count = 0;
    while(count < 1) {
      RequestHandler handler = new RequestHandler();
      serverConnection.acceptAndOpen(handler);
      System.out.println("Received OBEX connection " + (++count));
    }
    return ip;
  }

  /*
  * class RequestHandler
  * This class is used to handle any external requests to the local device.
  *
  */
  private static class RequestHandler extends ServerRequestHandler {
    //hcarver code
    Connection cconn;
    RemoteDevice remote;
    void connectionAccepted(Connection cconn) {
      System.out.println("Received OBEX connection!!!!");
      this.cconn = cconn;
      try {
        remote = RemoteDevice.getRemoteDevice(cconn);
        System.out.println("connected toBTAddress " + remote.getBluetoothAddress());
      } catch (IOException e) {
        System.out.println("OBEX Server error");
      }

    }
    //
    
    //hcarver code
        public int onConnect(HeaderSet request, HeaderSet reply) {
    			System.out.println("Received OBEX connection!");
    			return ResponseCodes.OBEX_HTTP_OK;
        }
    //

    //hcarver code
    public void onDisconnect(HeaderSet request, HeaderSet reply) {
      System.out.println("\nOBEX Disconnect signal from client\n");
    }
    //

    /*
    * onPut()
    * @args Operation op
    * @returns int
    * This function takes in Operation op and opens an input datastream whenever
    * the clients runs the Put command. It reads the input stream and stores the
    * data into file "hello.txt".
    */
    public int onPut(Operation op) {
      try {
        HeaderSet hs = op.getReceivedHeaders();
        String name = (String) hs.getHeader(HeaderSet.NAME);
        if (name != null) {
          System.out.println("put name:" + name);
          HeaderSet sendHeaders = createHeaderSet();
          sendHeaders.setHeader(HeaderSet.DESCRIPTION, name);
          op.sendHeaders(sendHeaders);
        }

        DataInputStream is = op.openDataInputStream();
        int length = (int)op.getLength();
        byte[] data = new byte[length];

        is.read(data);

        String s = new String(data, "UTF-8");
        System.out.println("---------Recieved\n");
        System.out.println("got:" + s.toString());
	ip = s;
        /*
        BufferedWriter bwr = new BufferedWriter(new FileWriter(new File("hello.txt")));
        bwr.write(buf.toString());

        bwr.flush();
        bwr.close();*/
        is.close();
        op.close();
        return ResponseCodes.OBEX_HTTP_OK;
      } catch (IOException e) {
        e.printStackTrace();
        System.out.println("OBEX Server error");
        return ResponseCodes.OBEX_HTTP_UNAVAILABLE;
      }
    }
    /*
    * onGet()
    * @args Operation op
    * @returns int
    * This function is called whenever the client runs a get command. The function
    * checks if the file has been modified and if it has, it propgates the changes
    * to the client via an output stream.
    */
    //hcarver code
    public int onGet(Operation op){
      
      File f = new File("hello.txt");
      try{
        StringBuffer stringBufferBefore = readFile(f);
        System.out.println("Waiting for changes (60 seconds)");
        Thread.sleep(10 * 1000);
        StringBuffer stringBufferAfter = readFile(f);
        if(stringBufferBefore.toString().equals(stringBufferAfter.toString()) == true)
        System.out.println("File not changed");
        else {
          System.out.println("File changed");
          try {
            HeaderSet hs = op.getReceivedHeaders();
            String name = (String) hs.getHeader(HeaderSet.NAME);

            byte data[] = String.valueOf(stringBufferAfter).getBytes();
            if (name != null) {
              HeaderSet sendHeaders = createHeaderSet();
              sendHeaders.setHeader(HeaderSet.DESCRIPTION, name);
              sendHeaders.setHeader(HeaderSet.LENGTH, new Long(data.length));
              op.sendHeaders(sendHeaders);
            }

            DataOutputStream os = op.openDataOutputStream();
            System.out.println("---------Sending\n");
            os.write(data);
            os.flush();
            os.close();
            op.close();
            return ResponseCodes.OBEX_HTTP_OK;
          } catch (IOException e) {
            System.out.println("OBEX Server onGet Sending error");
            return ResponseCodes.OBEX_HTTP_UNAVAILABLE;
          }

        }

      }catch(IOException | InterruptedException e){
        System.out.println("OBEX Server onGet error");
        return ResponseCodes.OBEX_HTTP_UNAVAILABLE;
      }
      return ResponseCodes.OBEX_HTTP_OK;
    }

    /*
    * StringBuffer readFile()
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

  }
}
