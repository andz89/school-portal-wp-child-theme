// function calculateTotal(maleSelector, femaleSelector, totalSelector) {
//   var male = parseInt(document.querySelector(maleSelector).innerText);
//   var female = parseInt(document.querySelector(femaleSelector).innerText);
//   document.querySelector(totalSelector).innerText = male + female;
//   console.log(male + female);
// }

// // Call the function for each grade
// calculateTotal("#kinder_male", "#kinder_female", "#kinder_total div div");
// calculateTotal("#grade_1_male", "#grade_1_female", "#grade_1_total div div");
// calculateTotal("#grade_2_male", "#grade_2_female", "#grade_2_total div div");
// calculateTotal("#grade_3_male", "#grade_3_female", "#grade_3_total div div");
// calculateTotal("#grade_4_male", "#grade_4_female", "#grade_4_total div div");
// calculateTotal("#grade_5_male", "#grade_5_female", "#grade_5_total div div");
// calculateTotal("#grade_6_male", "#grade_6_female", "#grade_6_total div div");

// function subCalculateTotal(lists_selector) {
//   let array_to_add = [];

//   // Ensure that the input is an array
//   if (Array.isArray(lists_selector)) {
//     // Loop through selectors and calculate the sum
//     lists_selector.forEach((selector) => {
//       let value = parseInt(document.querySelector(selector).innerText) || 0;
//       array_to_add.push(value);
//     });

//     // Calculate the total sum
//     return array_to_add.reduce(
//       (accumulator, currentValue) => accumulator + currentValue,
//       0
//     );
//   }
// }
// // Male selectors and total calculation
// let lists_male_selector = [
//   "#kinder_male",
//   "#grade_1_male",
//   "#grade_2_male",
//   "#grade_3_male",
//   "#grade_4_male",
//   "#grade_5_male",
//   "#grade_6_male",
// ];
// let sum_male = subCalculateTotal(lists_male_selector);
// document.querySelector("#total_selector_male div div").innerText = sum_male;
// // Female selectors and total calculation
// let lists_female_selector = [
//   "#kinder_female",
//   "#grade_1_female",
//   "#grade_2_female",
//   "#grade_3_female",
//   "#grade_4_female",
//   "#grade_5_female",
//   "#grade_6_female",
// ];
// let sum_female = subCalculateTotal(lists_female_selector);
// document.querySelector("#total_selector_female div div").innerText = sum_female;
// document.querySelector("#sub_total_enrollment div div").innerText =
//   sum_male + sum_female;
// document.querySelector("#total_enrollment div h2").innerText =
//   sum_male + sum_female;
// document.querySelector("#total_male div h2").innerText = sum_male;
// document.querySelector("#total_female div h2").innerText = sum_female;

document.addEventListener("DOMContentLoaded", function () {
  fetch("http://bvmces.local/wp-json/wp/v2/sy2024-2026?_fields=custom_fields")
    .then((response) => response.json())
    .then((data) => {
      setDataEnrollment(
        data,
        "#kinder_enrollment_container ",
        "Kinder",
        "table-kinder",
        "#table-kinder"
      );
      setDataEnrollment(
        data,
        "#grade1_enrollment_container ",
        "Grade 1",
        "table-grade-1",
        "#table-grade-1"
      );
    })
    .catch((error) => console.error("Error fetching data:", error));
});

function setDataEnrollment(
  data,
  grade_container,
  grade_level,
  table_name,
  table_id
) {
  const container = document.querySelector(grade_container);
  container.innerHTML += `<div><strong>${grade_level}</strong></div> <table id="${table_name}" border="1" cellpadding="10" cellspacing="0">
  <thead>
   
      <tr>
          <th>Section</th>
          <th>Boys</th>
          <th>Girls</th>
          <th>Total</th>
      </tr>
  </thead>
  <tbody>

    <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
     </tr>
  </tbody>
</table>`;

  const table_template = document.querySelector(`${table_id} tbody`); // Select the

  let male_enrollment_total = [];
  let female_enrollment_total = [];
  // Create a new row
  const newRow = table_template.insertRow();
  data.forEach((post) => {
    let grade_level_field_data = post.custom_fields.grade_level;
    let male_enrollment = post.custom_fields.male_enrollment;
    let female_enrollment = post.custom_fields.female_enrollment;
    let section = post.custom_fields.section;
    if (grade_level_field_data == grade_level) {
      // Create a new row
      const newRow = table_template.insertRow(0);

      // Insert cells in the new row
      const cell1 = newRow.insertCell(0); // Section
      const cell2 = newRow.insertCell(1); // Boys (male_enrollment)
      const cell3 = newRow.insertCell(2); // Girls (female_enrollment)
      const cell4 = newRow.insertCell(3); // Girls (female_enrollment)
      // Set the text content for each cell
      cell1.textContent = section;
      cell2.textContent = male_enrollment;
      cell3.textContent = female_enrollment;
      cell4.textContent =
        parseInt(male_enrollment) + parseInt(female_enrollment);

      male_enrollment_total.push(parseInt(male_enrollment));
      female_enrollment_total.push(parseInt(female_enrollment));
    }
  });
  // Call the last row
  const lastRow = table_template.rows[table_template.rows.length - 2];

  // Modify the last row by inserting a new cell or modifying its content
  if (lastRow) {
    let male = male_enrollment_total.reduce(
      (accumulator, currentValue) => accumulator + currentValue,
      0
    );
    let female = female_enrollment_total.reduce(
      (accumulator, currentValue) => accumulator + currentValue,
      0
    );
    lastRow.cells[1].textContent = male;
    lastRow.cells[2].textContent = female;
    lastRow.cells[3].textContent = male + female;
  }
}
