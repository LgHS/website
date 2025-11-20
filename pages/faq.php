<?php $faqs = json_decode(file_get_contents('datas/faq-data.json'), true); ?>

<article class="mb-6">
    <h3 class="bg-black text-white uppercase font-bold px-4 py-3 text-base mb-4">
        Questions fréquentes
    </h3>
    
    <div>
        <?php foreach ($faqs as $faq): ?>
        <div class="faq-item">
            <button class="faq-question">
                <span><?php echo htmlspecialchars($faq['question']); ?></span>
            </button>
            <div class="faq-answer">
                <div>
                    <p><?php echo $faq['answer']; ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</article>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const questions = document.querySelectorAll('.faq-question');
    
    questions.forEach(question => {
        question.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            const isActive = this.classList.contains('active');
            
            document.querySelectorAll('.faq-question').forEach(q => {
                q.classList.remove('active');
                q.nextElementSibling.classList.remove('active');
            });
            
            if (!isActive) {
                this.classList.add('active');
                answer.classList.add('active');
            }
        });
    });
});
</script>