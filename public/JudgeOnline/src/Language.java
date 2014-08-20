
import java.util.*;

public abstract class Language {

    public float time_limit;
    public double execution_time;
    public String veredict;
    public boolean time_out;
    HashMap<String, String> datos;
    public boolean runtime_error;
    Runtime runtime;
    Process process;
    int so;
    String file_compile;
    String file_execute;
    int reexecute; //veces que se va a reejecutar el código
    int solution; // ejecucion en que se encuentra la solucion

    Language(HashMap<String, String> datos) {
        this.time_limit = Float.parseFloat(datos.get("time_limit"));
        this.execution_time = 0;
        this.veredict = "";
        this.time_out = false;
        this.file_compile = "compile";
        this.file_execute = "run";
        this.datos = datos;
        process = null;
        this.reexecute = 4;
        int solution = 1;

        this.set_commands(Main.so); // 0 for linux, 1 for windows

    }

    public void set_commands(int so) {
        this.so = so;
        if (so == 0) { // for LINUX
            file_compile += ".sh";
            //   file_execute += ".sh";
        } else if (so == 1) {
            file_compile += ".bat";
            //  file_execute += ".bat";

        }
    }

    public String fe(int i) { //retorna el nombre del archivo de ejecución

        return this.file_execute + i + ((this.so == 0) ? ".sh" : ".bat");

    }

    public abstract void compile();

    public abstract String execute();
}
