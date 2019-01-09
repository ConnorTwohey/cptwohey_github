/*
* CSE 4340 Mobile Systems
* Connor Twohey
* 1001177282
* Project 1
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

  static final String serverUUID = "11111111111111111111111111111123";

  public static void main(String[] args) throws IOException {

    LocalDevice.getLocalDevice().setDiscoverable(DiscoveryAgent.GIAC);

    SessionNotifier serverConnection = (SessionNotifier) Connector.open("btgoep://localhost:"+ serverUUID + ";name=ObexExample");

    int count = 0;
    while(count < 2) {
      RequestHandler handler = new RequestHandler();
      serverConnection.acceptAndOpen(handler);
      System.out.println("Received OBEX connection " + (++count));
    }
  }

  private static class RequestHandler extends ServerRequestHandler {
    //hcarver code
    Connection cconn;
    RemoteDevice remote;
    void connectionAccepted(Connection cconn) {
      System.out.println("Received OBEX connection");
      this.cconn = cconn;
      try {
        remote = RemoteDevice.getRemoteDevice(cconn);
        System.out.println("connected toBTAddress " + remote.getBluetoothAddress());
      } catch (IOException e) {
        System.out.println("OBEX Server error");
      }

    }
    //


    //hcarver
    public int onConnect(HeaderSet request, HeaderSet reply) {
      System.out.println("OBEX onConnect");
      return ResponseCodes.OBEX_HTTP_OK;
    }
    //

    //hcarver code
    public void onDisconnect(HeaderSet request, HeaderSet reply) {
      System.out.println("\nOBEX Disconnect signal from client\n");
    }
    //

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
        StringBuffer buf = new StringBuffer(s);
        System.out.println("---------Recieved\n");
        System.out.println("got:" + buf.toString());

        File f = new File("hello.txt");
        f.setWritable(true, false);
        BufferedWriter bwr = new BufferedWriter(new FileWriter(f));
        bwr.write(buf.toString());

        bwr.flush();
        bwr.close();
        is.close();
        op.close();
        return ResponseCodes.OBEX_HTTP_OK;
      } catch (IOException e) {
        e.printStackTrace();
        System.out.println("OBEX Server error");
        return ResponseCodes.OBEX_HTTP_UNAVAILABLE;
      }
    }
    //hcarver code
    public int onGet(Operation op){
      File f = new File("hello.txt");
      try{
        while(true) {
          StringBuffer stringBufferBefore = readFile(f);
          System.out.println("Waiting for changes (60 seconds)");
          Thread.sleep(60 * 1000);
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
        }
      } catch(IOException | InterruptedException e){
        System.out.println("OBEX Server onGet error");
        return ResponseCodes.OBEX_HTTP_UNAVAILABLE;
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
}
