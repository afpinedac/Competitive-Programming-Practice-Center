
import java.net.ServerSocket;
import java.net.Socket;

public class Main {

    static int so = 0; // 0 for linux, 1 for windows

    public static void main(String[] args) throws Exception {
        ServerSocket ss = new ServerSocket(3029);

        System.out.println("Iniciando el Juez en Línea en el puerto  3029...");

        try {
            while (true) {
                Socket s = ss.accept();
                RequestThread rt = new RequestThread(s);
                rt.start();
            }
        } catch (Exception e) {
            e.printStackTrace();
        }

    }
}
