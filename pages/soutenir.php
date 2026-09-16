<article class="mb-6">
    <h3 class="bg-black text-white uppercase font-bold px-4 py-3 text-base mb-4">
        Soutenez le Liège Hackerspace
    </h3>
    <div class="leading-relaxed">
        <p>
            Le Liège Hackerspace est un espace communautaire indépendant qui vit principalement grâce au soutien de ses membres et donateurs. Votre contribution nous permet de maintenir nos 350m² d'atelier, d'acquérir et entretenir nos équipements, et de continuer à offrir un lieu d'apprentissage et de création accessible à tous.
        </p>
        
        <h4>Pourquoi nous soutenir ?</h4>
        
        <p><strong>En tant que particulier</strong>, votre don permet de :</p>
        <ul>
            <li>Maintenir un espace ouvert et accessible à tous</li>
            <li>Financer l'achat et l'entretien des machines (découpe laser, imprimantes 3D, CNC...)</li>
            <li>Organiser des ateliers gratuits et des événements communautaires</li>
            <li>Soutenir l'inclusion technologique et le partage des connaissances</li>
        </ul>
        
        <p><strong>En tant que professionnel ou entreprise</strong>, vous :</p>
        <ul>
            <li>Soutenez l'innovation et la formation technique locale</li>
            <li>Contribuez à un écosystème maker et tech dynamique</li>
            <li>Bénéficiez d'une visibilité auprès de notre communauté</li>
            <li>Aidez à former les talents techniques de demain</li>
        </ul>
        
        <p class="mt-4 text-sm italic">
            Le Liège Hackerspace est une ASBL belge (BE0649.448.256). Les dons de particuliers ne sont pas déductibles fiscalement, mais nous travaillons sur cette possibilité pour l'avenir.
        </p>
    </div>
</article>

<article class="mb-6">
    <h3 class="bg-black text-white uppercase font-bold px-4 py-3 text-base mb-4">
        Don par virement bancaire
    </h3>
    <div class="leading-relaxed">
        <h4>Générer un QR code de paiement</h4>
        <p class="mb-4">
            Scannez le QR code ci-dessous avec votre application bancaire mobile pour effectuer un virement instantané. Vous pouvez choisir le montant de votre don.
        </p>
        
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
            <div class="mb-4">
                <label for="donation-amount" class="block font-bold mb-3 text-center">Montant du don (€)</label>
                <div class="flex gap-2 flex-wrap mb-4 justify-center">
                    <button onclick="setAmount(5)" class="px-4 py-2 bg-white border-2 border-gray-300 hover:border-black hover:bg-black hover:text-white transition-colors font-bold">5€</button>
                    <button onclick="setAmount(10)" class="px-4 py-2 bg-white border-2 border-gray-300 hover:border-black hover:bg-black hover:text-white transition-colors font-bold">10€</button>
                    <button onclick="setAmount(25)" class="px-4 py-2 bg-white border-2 border-gray-300 hover:border-black hover:bg-black hover:text-white transition-colors font-bold">25€</button>
                    <button onclick="setAmount(50)" class="px-4 py-2 bg-white border-2 border-gray-300 hover:border-black hover:bg-black hover:text-white transition-colors font-bold">50€</button>
                    <button onclick="setAmount(100)" class="px-4 py-2 bg-white border-2 border-gray-300 hover:border-black hover:bg-black hover:text-white transition-colors font-bold">100€</button>
                </div>
                <div class="flex gap-3 items-center justify-center max-w-md mx-auto">
                    <label for="donation-amount" class="text-sm font-bold whitespace-nowrap">Montant libre :</label>
                    <input 
                        type="number" 
                        id="donation-amount" 
                        value="5"
                        min="1" 
                        step="1" 
                        placeholder="Entrez un montant"
                        class="w-32 px-4 py-2 border-2 border-gray-300 focus:border-black focus:outline-none text-lg text-center"
                        oninput="updateQRCode()"
                    >
                    <span class="text-lg font-bold">€</span>
                </div>
            </div>
            
            <div class="text-center">
                <div id="qr-code-container" class="inline-block bg-white p-4 border-4 border-black">
                    <img 
                        id="qr-code" 
                        src="https://epc-qr.eu/?bname=Liege%20Hackerspace&iban=BE58068910718879&euro=5&info=Don&bic=GKCCBEBB&cut=tlrb&logo=none" 
                        alt="QR Code de paiement"
                        class="w-64 h-64"
                    >
                </div>
                <p class="mt-4 text-sm text-gray-600">
                    Scannez ce QR code avec votre app bancaire pour payer
                </p>
            </div>
        </div>

        <h4>Virement manuel</h4>
        <div class="text-center my-6 py-4 bg-gray-50 border-2 border-black">
            <p class="font-bold mb-1">Liège Hackerspace ASBL</p>
            <p class="text-sm mb-1">IBAN: BE58 0689 1071 8879</p>
            <p class="text-sm mb-1">BIC: GKCCBEBB</p>
            <p class="text-sm">Communication: Don</p>
        </div>
    </div>
</article>

<article class="mb-6">
    <h3 class="bg-black text-white uppercase font-bold px-4 py-3 text-base mb-4">
        Autres moyens de soutien
    </h3>
    <div class="leading-relaxed">
        <h4>Cryptomonnaies</h4>
        <p class="mb-2">
            Vous pouvez également nous soutenir en cryptomonnaies. Contactez-nous à <a href="mailto:ping@lghs.be">ping@lghs.be</a> pour obtenir nos adresses de portefeuille.
        </p>
        
        <h4>Partenariats et sponsoring</h4>
        <p class="mb-2">
            Vous êtes une entreprise et souhaitez nous soutenir de manière plus conséquente ? Parlons-en ! Nous proposons différentes formules de partenariat adaptées à vos besoins.
        </p>
        <p>
            <a href="/contact">Contactez-nous</a> pour en discuter.
        </p>
        
        <h4>Dons en nature</h4>
        <p class="mb-2">
            Vous avez du matériel (outils, machines, composants électroniques, matières premières...) dont vous n'avez plus l'utilité ? Nous acceptons les dons en nature, sous réserve qu'ils correspondent à nos besoins. N'hésitez pas à nous <a href="/contact">contacter</a> avant de vous déplacer.
        </p>
    </div>
</article>

<script>
function setAmount(amount) {
    document.getElementById('donation-amount').value = amount;
    updateQRCode();
}

function updateQRCode() {
    const amount = document.getElementById('donation-amount').value;
    const qrImage = document.getElementById('qr-code');
    
    if (amount && amount > 0) {
        const baseUrl = 'https://epc-qr.eu/';
        const params = new URLSearchParams({
            bname: 'Liege Hackerspace',
            iban: 'BE58068910718879',
            euro: amount,
            info: 'Don',
            bic: 'GKCCBEBB',
            cut: 'tlrb',
            logo: 'none'
        });
        
        qrImage.src = baseUrl + '?' + params.toString();
    } else {
        // QR code sans montant si rien n'est entré
        qrImage.src = 'https://epc-qr.eu/?bname=Liege%20Hackerspace&iban=BE58068910718879&euro=&info=Don&bic=GKCCBEBB&cut=tlrb&logo=none';
    }
}
</script>