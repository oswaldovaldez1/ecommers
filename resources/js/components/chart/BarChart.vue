<script>
import { Bar } from "vue-chartjs";

export default {
  extends: Bar,
  props: ["data"],
  mounted() {
    let label = [];
    let datax = [];

    this.data.forEach((dato) => {
      label.push(dato.Mes);
      datax.push(dato.contador);
    });
    this.renderChart(
      {
        labels: label,
        datasets: [
          {
            label: "Compras Mensuales",
            backgroundColor: "#f87979",
            data: datax,
          },
        ],
      },
      { responsive: true, maintainAspectRatio: false }
    );
  },
};
</script>
