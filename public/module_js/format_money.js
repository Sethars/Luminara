export function formatMoney (amount) {
  if (amount >= 1_000_000_000) {
    return (amount / 1_000_000_000).toFixed(1).replace(/\.0$/, "").replace(".", ",") + "M";
  } else if (amount >= 1_000_000) {
    return (amount / 1_000_000).toFixed(1).replace(/\.0$/, "").replace(".", ",") + "Jt";
  } else if (amount >= 1_000) {
    return (amount / 1_000).toFixed(1).replace(/\.0$/, "").replace(".", ",") + "Rb";
  } else {
    return amount.toString();
  }
}