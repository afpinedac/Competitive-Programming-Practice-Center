import java.net.ServerSocket;
import java.net.Socket;
import java.util.ArrayList;
import java.util.LinkedList;
import java.util.Queue;

public class Main {

    static int so = 0; // 0 for linux, 1 for windows
    static Queue<Socket> envios;
    
    
    public static void main(String[] args) throws Exception {
      
        ServerSocket ss = new ServerSocket(3029);
        envios = new LinkedList<Socket>();
        ExecuterThread et = new ExecuterThread();
        et.start();

        System.out.println("Iniciando el Juez en Línea en el puerto  3029...");
        try {
            while (true) {
                Socket s = ss.accept();
              //  System.out.println("se recibio uno nuevo : " + envios.size());
                envios.add(s);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }

    }
}
