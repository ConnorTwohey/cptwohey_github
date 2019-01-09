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

public class WifiClient{
  public static void main(String[] args) throws IOException, InterruptedException{
    int portNumber = 50005;
    String ip = null;
    ip = OBEXServer.main(null);
    //ip = "192.168.43.214";

    System.out.println("\nConnecting to ip: " + ip);
    Socket clientSocket = new Socket(ip, portNumber);
    System.out.println("\nEstablished a connection!\n");
    //InputStream input = clientSocket.getInputStream();
    OutputStream output = clientSocket.getOutputStream();
   
    //Open file "hello.txt"
    File f = new File("hello.txt");
    f.setWritable(true, false);	
 
    //Send first instance of file
    StringBuffer initialString = readFile(f);
    byte[] initialFileData = String.valueOf(initialString).getBytes();
    output.write(initialFileData);

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
		output.write(data);
	}
	
    }

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
    stringBuffer.append("\0");
    return stringBuffer;
  }

}


