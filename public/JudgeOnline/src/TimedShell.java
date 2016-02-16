
public class TimedShell extends Thread {

    Process process;
    float time_limit;
    Language language;

    TimedShell(Language l, Process p, float time_limit) {
        this.language = l;
        this.process = p;
        this.time_limit = time_limit;

    }

    public void run() {

        try {

            double ini = System.currentTimeMillis();
            double fin = ini;
            double time = 0;
            this.language.time_out = false;
            double total_time = time_limit * 1500;
            double x = (total_time/1000.0);
            while (true) {
                time = fin - ini;
                if (time > total_time) {  // hay time limit ?
                    this.language.time_out = true;
                    break;
                } else {
                    try {
                        process.exitValue();  //lanzará una excepción si aún no ha terminado, lo cual hará que siga iterando.
                        this.language.execution_time = (time) / 1000.0;
                        this.language.execution_time = Math.min(this.language.execution_time + 0.001, x-(Math.random()*0.01));
                        break;
                    } catch (Exception e) {
                        //   nada que acaba
                    }
                }

                fin = System.currentTimeMillis();
            }

            if (this.language.time_out) {
                try {
                    process.exitValue(); //el proceso ya debio terminar si no es asi, es que aun se esta ejecutando
                } catch (Exception e) {

                    process.destroy();
                }
            }

        } catch (Exception e) {
            e.printStackTrace();
        }

    }
}
