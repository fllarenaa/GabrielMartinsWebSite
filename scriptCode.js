document.addEventListener("DOMContentLoaded", function() {
   // Seleciona todos os botões de confirmar entrega
   const botoesConfirmar = document.querySelectorAll(".confirmar button");

   botoesConfirmar.forEach(botao => {
      botao.addEventListener("click", async function() {
         const { value: pin } = await Swal.fire({
            title: 'Confirmação de Entrega',
            text: 'Digite o PIN de confirmação:',
            input: 'password',
            inputPlaceholder: 'PIN de 4 dígitos',
            inputAttributes: {
               maxlength: 4,
               autocapitalize: 'off',
               autocorrect: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar',
            background: '#1c1c1c',
            color: '#fff',
            confirmButtonColor: '#f7b500'
         });

         if (pin === '1234') {
            Swal.fire({
               icon: 'success',
               title: 'Entrega Confirmada!',
               text: `PIN digitado: ${pin}`,
               confirmButtonColor: '#f7b500',
               background: '#1c1c1c',
               color: '#fff'
            });
         } else if (pin === '') {
            Swal.fire({
               icon: 'error',
               title: 'PIN vazio!',
               text: 'Por favor, insira o PIN para confirmar.',
               confirmButtonColor: '#f7b500',
               background: '#1c1c1c',
               color: '#fff'
            });
         }
      });
   });
});