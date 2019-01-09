/*
* CSE 4340 Mobile Systems
* Connor Twohey
* 1001177282
* Project 1
* --------------------------------------------------------------
* Majority of code from bluecove overview examples
* http://www.bluecove.org/bluecove/apidocs/overview-summary.html
*/

import java.io.IOException;
import java.util.Enumeration;
import java.util.Vector;
import javax.bluetooth.*;
import java.util.Scanner;

/**
*
* Minimal Services Search example.
*/
public class ServicesSearch {

  static final UUID OBEX_FILE_TRANSFER = new UUID(0x1106);

  static final UUID OBEX_OBJECT_PUSH = new UUID(0x1105);

  public static final Vector/*<String>*/ serviceFound = new Vector();

  public static void main(String[] args) throws IOException, InterruptedException {

    // First run RemoteDeviceDiscovery and use discoved device
    RemoteDeviceDiscovery.main(null);

    serviceFound.clear();

    UUID serviceUUID = OBEX_FILE_TRANSFER;
    if ((args != null) && (args.length > 0)) {
      serviceUUID = new UUID(args[0], false);
    }

    final Object serviceSearchCompletedEvent = new Object();

    DiscoveryListener listener = new DiscoveryListener() {

      public void deviceDiscovered(RemoteDevice btDevice, DeviceClass cod) {
      }

      public void inquiryCompleted(int discType) {
      }

      public void servicesDiscovered(int transID, ServiceRecord[] servRecord) {
        for (int i = 0; i < servRecord.length; i++) {
          String url = servRecord[i].getConnectionURL(ServiceRecord.NOAUTHENTICATE_NOENCRYPT, false);
          if (url == null) {
            continue;
          }
          serviceFound.add(url);
          DataElement serviceName = servRecord[i].getAttributeValue(0x0100);
          if (serviceName != null) {
            System.out.println("service " + serviceName.getValue() + " found " + url);
          } else {
            System.out.println("service found " + url);
          }
        }
      }

      public void serviceSearchCompleted(int transID, int respCode) {
        System.out.println("service search completed!");
        synchronized(serviceSearchCompletedEvent){
          serviceSearchCompletedEvent.notifyAll();
        }
      }

    };

    UUID[] searchUuidSet = new UUID[] { serviceUUID };
    int[] attrIDs =  new int[] {
      0x0100 // Service name
    };

    Scanner scan = new Scanner(System.in);
    System.out.print("Enter index of device desired: ");
    int desiredDeviceIndex = Integer.parseInt(scan.nextLine());
    if(desiredDeviceIndex != -1){
      RemoteDevice btDevice = (RemoteDevice)RemoteDeviceDiscovery.devicesDiscovered.get(desiredDeviceIndex);

      synchronized(serviceSearchCompletedEvent) {
        System.out.println("search services on " + btDevice.getBluetoothAddress() + " " + btDevice.getFriendlyName(false));
        LocalDevice.getLocalDevice().getDiscoveryAgent().searchServices(attrIDs, searchUuidSet, btDevice, listener);
        serviceSearchCompletedEvent.wait();
      }

    }

  }

}
