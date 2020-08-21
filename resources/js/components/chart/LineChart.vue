<script>
import { Line } from "vue-chartjs";

export default {
  extends: Line,
  props: ["data"],
  mounted() {
    let label = [];
    let datax = [];

    this.data.forEach((dato) => {
      label.push(dato.Mes);
      datax.push(dato.total);
    });
    this.renderChart(
      {
        labels: label,
        datasets: [
          {
            label: "Total",
            data: datax,
            backgroundColor: "transparent",
            borderColor: "rgba(1, 116, 188, 0.50)",
            pointBackgroundColor: "rgba(171, 71, 188, 1)",
          },
        ],
      },
      {
        responsive: true,
        maintainAspectRatio: true,
        title: {
          display: true,
          text: "Total Compras Mensuales",
        },
      }
    );
  },
};
</script>
