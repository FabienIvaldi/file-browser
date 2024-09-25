$(document).ready(function () {
    $.ajax({
        url: 'json.php', // Chemin vers le fichier JSON
        dataType: 'json',
        success: function (data) {
            // Parcours de chaque recette dans les données JSON
            $.each(data, function (index, recipe) {
                var recipeHtml = `
                    <div class="recipe">
                    <input type="checkbox" id="horns" name="horns" />
                        <h2>${recipe.title}</h2>
                        <p>${recipe.instructions}</p>
                        <h3>Ingredients:</h3>
                        <ul>`;

                // Parcours de chaque ingrédient de la recette
                $.each(recipe.ingredients, function (index, ingredient) {
                    recipeHtml += `<li>${ingredient.quantity} ${ingredient.unit} ${ingredient.name}</li>`;
                });


                recipeHtml += `</ul></div>`;

                $('#recipes').append(recipeHtml); // Ajout de la recette à la page
            });
        }
    });
});
