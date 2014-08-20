
import java.util.*;

public class Cpp extends Language {

    public Cpp(HashMap<String, String> datos) {
        super(datos);
    }

    @Override
    public void compile() {
        try {

            RequestThread.write_in_file(datos.get("path") + "/main.cpp", datos.get("algoritmo"));
            RequestThread.write_in_file(datos.get("path") + "/" + file_compile, "cd \"" + datos.get("path") + "\"\n" + "g++ main.cpp -o main 2>err.txt");
            Runtime rt = Runtime.getRuntime();
            if (this.so == 0) { //si es linux se le dan los permisos y se ejecuta
                process = rt.exec("chmod +x " + datos.get("path") + "/" + file_compile); // damos permiso a el compiler script
                process.waitFor();
            }
            process = rt.exec(datos.get("path") + "/" + file_compile); // execute the compiler script
            process.waitFor();

            //verificamos que exista el archivo llamado Main.class
            /*File f = new File(datos.get("path") + "/Main.class");
             if (!f.exists()) { //el usuario mando el archivo con otro nombre y por eso el compilador no lo creo bien
             RequestThread.write_in_file(datos.get("path") + "/err.txt", "error");
             }*/
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @Override
    public String execute() {

        try {
            // create the execution script            
            RequestThread.write_in_file(datos.get("path") + "/in.txt", datos.get("in") == null ? "" : datos.get("in"));

            for (int i = 1; i <= reexecute; i++) {
                RequestThread.write_in_file(datos.get("path") + "/" + fe(i), "cd \"" + datos.get("path") + "\"\n" + "./main <in.txt> out" + i + ".txt");
            }
            //out.write("chroot .\n");
            //Process p = r.exec("chmod +x " + dir + "/run.sh");
            //p.waitFor();
            Runtime rt = Runtime.getRuntime();
            if (this.so == 0) { //si es linux le damos los permosos

                for (int i = 1; i <= reexecute; i++) {
                    process = rt.exec("chmod +x " + datos.get("path") + "/" + fe(i)); // damos permisos
                    process.waitFor(); //esperamos

                }
            }

            int t = 1;
            while (t <= reexecute) {
                process = rt.exec(datos.get("path") + "/" + fe(t)); // execute the script 
                this.time_out = false;
                TimedShell shell = new TimedShell(this, process, Float.parseFloat(datos.get("time_limit")));
                shell.start();
                process.waitFor();

                if (!this.time_out) {  // si no hubo time limit
                    String error_stream = RequestThread.get_string_from_stream(process.getErrorStream());

                    if (!error_stream.trim().equals("")) { //si hubo algun error en la salida      
                        
                        this.runtime_error = true;
                        return error_stream;
                    }
                    this.solution = t;
                    break;
                }
                t++;
            }

        } catch (Exception e) {
            System.err.println("Error ejecutando el algoritmo");
        }
        return "";
    }

}
