/*
* CSE 4340 Mobile Systems
* Connor Twohey
* 1001177282
* Project 1
* --------------------------------------------------------------
* Majority of code from bluecove overview examples
* http://www.bluecove.org/bluecove/apidocs/overview-summary.html
*/


import javax.bluetooth.*;
import java.io.IOException;
import java.util.Vector;
import java.lang.String;

/**
* Minimal Device Discovery example.
*/
public class RemoteDeviceDiscovery {

  public static final Vector/*<RemoteDevice>*/ devicesDiscovered = new Vector();

  public static void main(String[] args) throws IOException, InterruptedException {

    final Object inquiryCompletedEvent = new Object();

    devicesDiscovered.clear();

    LocalDevice.getLocalDevice().setDiscoverable(DiscoveryAgent.GIAC);

    DiscoveryListener listener = new DiscoveryListener() {
      public void deviceDiscovered(RemoteDevice btDevice, DeviceClass cod) {


        System.out.println("Device " + btDevice.getBluetoothAddress() + " found");
        devicesDiscovered.addElement(btDevice);
        try {
          System.out.println("     name " + btDevice.getFriendlyName(false));



        } catch (IOException cantGetDeviceName) {
        }
      }

      public void inquiryCompleted(int discType) {
        System.out.println("Device Inquiry completed!");
        synchronized(inquiryCompletedEvent){
          inquiryCompletedEvent.notifyAll();
        }
      }

      public void serviceSearchCompleted(int transID, int respCode) {
      }

      public void servicesDiscovered(int transID, ServiceRecord[] servRecord) {
      }
    };

    synchronized(inquiryCompletedEvent) {
      boolean started = LocalDevice.getLocalDevice().getDiscoveryAgent().startInquiry(DiscoveryAgent.GIAC, listener);
      if (started) {
        System.out.println("wait for device inquiry to complete...");
        inquiryCompletedEvent.wait();
        System.out.println(devicesDiscovered.size() +  " device(s) found");
      }
    }

  }

}
