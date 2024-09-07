function calculateTotal(maleSelector, femaleSelector, totalSelector) {
  var male = parseInt(document.querySelector(maleSelector).innerText);
  var female = parseInt(document.querySelector(femaleSelector).innerText);
  document.querySelector(totalSelector).innerText = male + female;
  console.log(male + female);
}

// Call the function for each grade
calculateTotal("#kinder_male", "#kinder_female", "#kinder_total div div");
calculateTotal("#grade_1_male", "#grade_1_female", "#grade_1_total div div");
calculateTotal("#grade_2_male", "#grade_2_female", "#grade_2_total div div");
calculateTotal("#grade_3_male", "#grade_3_female", "#grade_3_total div div");
calculateTotal("#grade_4_male", "#grade_4_female", "#grade_4_total div div");
calculateTotal("#grade_5_male", "#grade_5_female", "#grade_5_total div div");
calculateTotal("#grade_6_male", "#grade_6_female", "#grade_6_total div div");

function subCalculateTotal(lists_selector) {
  let array_to_add = [];

  // Ensure that the input is an array
  if (Array.isArray(lists_selector)) {
    // Loop through selectors and calculate the sum
    lists_selector.forEach((selector) => {
      let value = parseInt(document.querySelector(selector).innerText) || 0;
      array_to_add.push(value);
    });

    // Calculate the total sum
    return array_to_add.reduce(
      (accumulator, currentValue) => accumulator + currentValue,
      0
    );
  }
}
// Male selectors and total calculation
let lists_male_selector = [
  "#kinder_male",
  "#grade_1_male",
  "#grade_2_male",
  "#grade_3_male",
  "#grade_4_male",
  "#grade_5_male",
  "#grade_6_male",
];
let sum_male = subCalculateTotal(lists_male_selector);
document.querySelector("#total_selector_male div div").innerText = sum_male;
// Female selectors and total calculation
let lists_female_selector = [
  "#kinder_female",
  "#grade_1_female",
  "#grade_2_female",
  "#grade_3_female",
  "#grade_4_female",
  "#grade_5_female",
  "#grade_6_female",
];
let sum_female = subCalculateTotal(lists_female_selector);
document.querySelector("#total_selector_female div div").innerText = sum_female;
document.querySelector("#total_enrollment div div").innerText =
  sum_male + sum_female;
