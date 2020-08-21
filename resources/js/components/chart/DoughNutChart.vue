<script>
import { Doughnut } from "vue-chartjs";

export default {
  extends: Doughnut,
  props: ["data"],
  mounted() {
    let label = [];
    let datax = [];
    this.data.forEach((dato) => {
      label.push(dato.status);
      datax.push(dato.total);
    });
    this.renderChart(
      {
        labels: label,
        datasets: [
          {
            backgroundColor: ["#41B883", "#E46651", "#00D8FF", "#DD1B16"],
            data: datax,
          },
        ],
      },
      {
        responsive: true,
        maintainAspectRatio: false,
        title: {
          display: true,
          text: "Estatus de pedidos",
        },
      }
    );
  },
};
</script>
