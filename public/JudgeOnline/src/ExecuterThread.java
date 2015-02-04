
public class ExecuterThread extends Thread {

  static boolean executing;

  @Override
  public void run() {
    executing = false;

    while (true) {
      if (Main.envios.size() > 0) {
        System.out.print("");
      }
      if (!executing && Main.envios.size() > 0) {
        executing = true;
        RequestThread rt = new RequestThread(Main.envios.poll());
        rt.start();
        
      }
    }
  }
}
