<?php
require('../../fpdf186/fpdf.php');

class PDF extends FPDF {
    // Page header
    function Header() {
        // Logo area with blue background
        $this->SetFillColor(157, 25, 25); // Dark blue color for German
        $this->Rect(0, 0, 210, 15, 'F');
        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 15, 'GERMAN FOR BEGINNERS', 0, 1, 'C');
        $this->Ln(5);
    }

    // Page footer
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
    
    // Title
    function LessonTitle($num, $title) {
        $this->SetFont('Arial', 'B', 14);
        $this->SetFillColor(52, 73, 94); // Dark blue-gray
        $this->SetTextColor(255);
        $this->Cell(0, 10, "Lesson $num: $title", 1, 1, 'C', true);
        $this->Ln(3);
    }
    
    // Section
    function SectionTitle($title) {
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(44, 62, 80); // Dark blue
        $this->Cell(0, 8, $title, 0, 1);
        $this->SetTextColor(0);
    }

    // Vocabulary table with pronunciation
    function PronunciationTable($german, $pronunciation, $english) {
        $this->SetFillColor(240, 240, 240); // Light gray
        $this->SetFont('Arial', 'B', 11);
        $this->SetTextColor(0);
        
        $w1 = 60; // German column width
        $w2 = 65; // Pronunciation column width
        $w3 = 60; // English column width
        
        // Header
        $this->Cell($w1, 7, 'German', 1, 0, 'C', true);
        $this->Cell($w2, 7, 'Pronunciation', 1, 0, 'C', true);
        $this->Cell($w3, 7, 'English', 1, 1, 'C', true);
        
        // Data
        $this->SetFont('Arial', '', 11);
        $fill = false;
        foreach($german as $i => $g) {
            $this->Cell($w1, 6, $g, 'LR', 0, 'L', $fill);
            $this->Cell($w2, 6, $pronunciation[$i], 'LR', 0, 'L', $fill);
            $this->Cell($w3, 6, $english[$i], 'LR', 1, 'L', $fill);
            $fill = !$fill;
        }
        // Closing line
        $this->Cell($w1 + $w2 + $w3, 0, '', 'T');
        $this->Ln(5);
    }
    
    // Bullet points - proper bullet character
    function BulletPoint($txt) {
        $this->SetFont('ZapfDingbats', '', 10);
        $this->Cell(5, 6, chr(108), 0, 0);
        $this->SetFont('Arial', '', 11);
        $this->MultiCell(0, 6, $txt);
    }
}

// Create PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetMargins(15, 15, 15);

// Cover Page
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 24);
$pdf->SetTextColor(44, 62, 80);
$pdf->Cell(0, 40, '', 0, 1);
$pdf->Cell(0, 20, 'GERMAN FOR BEGINNERS', 0, 1, 'C');
$pdf->SetFont('Arial', '', 14);
$pdf->SetTextColor(0);
$pdf->Cell(0, 10, 'A Comprehensive Guide', 0, 1, 'C');
$pdf->Cell(0, 20, '', 0, 1);
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(0, 10, 'Each lesson on its own page', 0, 1, 'C');
$pdf->Cell(0, 10, 'With pronunciation guides for correct reading', 0, 1, 'C');

// Lesson 1
$pdf->AddPage();
$pdf->LessonTitle('1', 'Getting Started');

$pdf->SetFont('Arial', 'I', 11);
$pdf->MultiCell(0, 7, "Begin your German journey with essential phrases and pronunciation.");
$pdf->Ln(3);

$pdf->SectionTitle('German Pronunciation Basics:');
$pdf->SetFont('Arial', '', 11);
$pdf->BulletPoint("Vowels: a (ah), e (eh), i (ee), o (oh), u (oo)");
$pdf->BulletPoint("Umlauts: ä (eh), ö (er), ü (ue as in French 'tu')");
$pdf->BulletPoint("Diphthongs: ei/ai (eye), au (ow), eu/äu (oy)");
$pdf->BulletPoint("Consonants: v (f), w (v), j (y), z (ts), ch (soft sound like in 'loch')");
$pdf->BulletPoint("ß (eszett): Pronounced like a sharp 's' as in 'less'");
$pdf->BulletPoint("German is pronounced as it's written - very consistent!");
$pdf->Ln(3);

$pdf->SectionTitle('Essential Phrases:');
$german = [
    'Guten Tag', 
    'Guten Abend', 
    'Auf Wiedersehen', 
    'Danke', 
    'Bitte',
    'Gern geschehen', 
    'Wie geht es Ihnen?', 
    'Sehr gut', 
    'Entschuldigung', 
    'Freut mich'
];
$pronunciation = [
    'GOO-ten tahk', 
    'GOO-ten AH-bent', 
    'owf VEE-der-zay-en', 
    'DAHN-kuh', 
    'BIT-tuh',
    'gern geh-SHAY-en', 
    'vee gayt es EE-nen', 
    'zayr goot', 
    'ent-SHOOL-di-goong', 
    'froyt mikh'
];
$english = [
    'Hello', 
    'Good evening', 
    'Goodbye',
    'Thank you', 
    'Please/You\'re welcome',
    'You\'re welcome', 
    'How are you? (formal)', 
    'Very good', 
    'Excuse me',
    'Nice to meet you'
];
$pdf->PronunciationTable($german, $pronunciation, $english);

$pdf->SectionTitle('Numbers 0-10:');
$numbers_de = ['null', 'eins', 'zwei', 'drei', 'vier', 'fünf', 'sechs', 'sieben', 'acht', 'neun', 'zehn'];
$numbers_pron = ['nool', 'ayns', 'tsvay', 'dry', 'feer', 'fuenf', 'zeks', 'ZEE-ben', 'akht', 'noyn', 'tsayn'];
$numbers_en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
$pdf->PronunciationTable($numbers_de, $numbers_pron, $numbers_en);

// Lesson 2
$pdf->AddPage();
$pdf->LessonTitle('2', 'Basic Conversations');

$pdf->SetFont('Arial', 'I', 11);
$pdf->MultiCell(0, 7, "Learn how to build basic conversations with personal pronouns and essential verbs.");
$pdf->Ln(3);

$pdf->SectionTitle('Personal Pronouns:');
$pronouns = ['ich', 'du', 'er/sie/es', 'wir', 'ihr'];
$pronouns_pron = ['ikh', 'doo', 'ayr/zee/es', 'veer', 'eer'];
$pronoun_trans = ['I', 'you (informal)', 'he/she/it', 'we', 'you (plural)'];
$pdf->PronunciationTable($pronouns, $pronouns_pron, $pronoun_trans);

$pdf->SectionTitle('Basic Verbs (Present Tense):');

// SEIN table
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 7, 'SEIN (to be) - zayn', 0, 1);
$pdf->SetFont('Arial', '', 11);

$sein_german = ['ich bin', 'du bist', 'er/sie/es ist', 'wir sind', 'ihr seid'];
$sein_pron = ['ikh bin', 'doo bist', 'ayr/zee/es ist', 'veer zint', 'eer zayt'];
$sein_english = ['I am', 'you are', 'he/she/it is', 'we are', 'you are'];

$pdf->PronunciationTable($sein_german, $sein_pron, $sein_english);

// HABEN table
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 7, 'HABEN (to have) - HAH-ben', 0, 1);
$pdf->SetFont('Arial', '', 11);

$haben_german = ['ich habe', 'du hast', 'er/sie/es hat', 'wir haben', 'ihr habt'];
$haben_pron = ['ikh HAH-buh', 'doo hast', 'ayr/zee/es hat', 'veer HAH-ben', 'eer hapt'];
$haben_english = ['I have', 'you have', 'he/she/it has', 'we have', 'you have'];

$pdf->PronunciationTable($haben_german, $haben_pron, $haben_english);

$pdf->SectionTitle('Days of the Week:');
$days = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'];
$days_pron = ['MON-tahk', 'DEENS-tahk', 'MIT-vokh', 'DON-ers-tahk', 'FRY-tahk', 'ZAMS-tahk', 'ZON-tahk'];
$days_trans = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$pdf->PronunciationTable($days, $days_pron, $days_trans);

// Lesson 3
$pdf->AddPage();
$pdf->LessonTitle('3', 'Grammar Basics');

$pdf->SetFont('Arial', 'I', 11);
$pdf->MultiCell(0, 7, "Master the basic building blocks of German grammar.");
$pdf->Ln(3);

$pdf->SectionTitle('Articles:');
$pdf->SetFont('Arial', '', 11);

// Definite articles
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 7, 'Definite Articles (the):', 0, 1);
$pdf->SetFont('Arial', '', 11);

$def_articles = ['der', 'die', 'das', 'die'];
$def_pron = ['dayr', 'dee', 'das', 'dee'];
$def_eng = ['the (masculine singular)', 'the (feminine singular)', 'the (neuter singular)', 'the (plural)'];
$pdf->PronunciationTable($def_articles, $def_pron, $def_eng);

// Indefinite articles
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 7, 'Indefinite Articles (a/an/some):', 0, 1);
$pdf->SetFont('Arial', '', 11);

$indef_articles = ['ein', 'eine', 'ein', 'keine'];
$indef_pron = ['ayn', 'AY-nuh', 'ayn', 'KAY-nuh'];
$indef_eng = ['a/an (masculine/neuter)', 'a/an (feminine)', 'a/an (neuter)', 'no/none (all genders)'];
$pdf->PronunciationTable($indef_articles, $indef_pron, $indef_eng);

$pdf->SectionTitle('Question Words:');
$questions = ['Was?', 'Wer?', 'Wo?', 'Wann?', 'Warum?', 'Wie?'];
$questions_pron = ['vas', 'vayr', 'vo', 'van', 'vah-ROOM', 'vee'];
$question_trans = ['What?', 'Who?', 'Where?', 'When?', 'Why?', 'How?'];
$pdf->PronunciationTable($questions, $questions_pron, $question_trans);

$pdf->SectionTitle('Common Adjectives:');
$adj_de = ['groß', 'klein', 'gut', 'schlecht', 'schön', 'neu', 'alt', 'jung'];
$adj_pron = ['grohs', 'klayn', 'goot', 'shlekht', 'shern', 'noy', 'alt', 'yoong'];
$adj_eng = ['big/tall', 'small/little', 'good', 'bad', 'beautiful', 'new', 'old', 'young'];

$pdf->PronunciationTable($adj_de, $adj_pron, $adj_eng);

// Lesson 4
$pdf->AddPage();
$pdf->LessonTitle('4', 'Practical Applications');

$pdf->SetFont('Arial', 'I', 11);
$pdf->MultiCell(0, 7, "Put your German to use in real-world situations.");
$pdf->Ln(3);

$pdf->SectionTitle('Restaurant Phrases:');
$restaurant = [
    'Einen Tisch für zwei, bitte', 
    'Die Rechnung, bitte',
    'Ich möchte bestellen', 
    'Es ist lecker',
    'Können Sie mir bringen...?', 
    'Was ist das Tagesgericht?'
];
$rest_pron = [
    'AY-nen tish fuer tsvay, BIT-tuh',
    'dee REKH-noong, BIT-tuh',
    'ikh MERKH-tuh beh-SHTEL-len',
    'es ist LEK-ker',
    'KERN-nen zee meer BRING-en',
    'vas ist das TAH-ges-ge-rikht'
];
$rest_trans = [
    'A table for two, please', 
    'The check, please',
    'I would like to order', 
    'It is delicious',
    'Could you bring me...?', 
    'What is today\'s special?'
];
$pdf->PronunciationTable($restaurant, $rest_pron, $rest_trans);

$pdf->SectionTitle('Shopping Vocabulary:');
$shopping = [
    'Wie viel kostet das?', 
    'Haben Sie eine andere Farbe?',
    'Das ist zu teuer', 
    'Billiger, bitte',
    'Welche Größe ist das?', 
    'Ich nehme es'
];
$shop_pron = [
    'vee feel KOS-tet das',
    'HAH-ben zee AY-nuh AN-duh-ruh FAR-buh',
    'das ist tsoo TOY-er',
    'BILL-ig-er, BIT-tuh',
    'VEL-khuh GRERS-uh ist das',
    'ikh NAY-muh es'
];
$shop_trans = [
    'How much does it cost?', 
    'Do you have another color?',
    'It\'s too expensive', 
    'Less expensive, please',
    'What size is it?', 
    'I\'ll take it'
];
$pdf->PronunciationTable($shopping, $shop_pron, $shop_trans);

$pdf->SectionTitle('Emergency Phrases:');
$emergency = [
    'Hilfe!', 
    'Ich brauche einen Arzt',
    'Wo ist das Krankenhaus?', 
    'Rufen Sie die Polizei',
    'Ich habe mich verirrt', 
    'Ich brauche Hilfe'
];
$emerg_pron = [
    'HILL-fuh',
    'ikh BROW-khuh AY-nen artst',
    'vo ist das KRANK-en-hows',
    'ROO-fen zee dee po-li-TSAY',
    'ikh HAH-buh mikh fer-EERt',
    'ikh BROW-khuh HILL-fuh'
];
$emerg_trans = [
    'Help!', 
    'I need a doctor',
    'Where is the hospital?', 
    'Call the police',
    'I am lost', 
    'I need help'
];
$pdf->PronunciationTable($emergency, $emerg_pron, $emerg_trans);

// Practice Exercises
$pdf->AddPage();
$pdf->LessonTitle('5', 'Practice Exercises');

$pdf->SetFont('Arial', 'I', 11);
$pdf->MultiCell(0, 7, "Test your knowledge with these exercises covering all lessons.");
$pdf->Ln(3);

$pdf->SectionTitle('Matching Exercise:');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(80, 7, 'German (Pronunciation)', 0, 0);
$pdf->Cell(80, 7, 'English', 0, 1);

$pdf->Cell(10, 7, '1.', 0, 0);
$pdf->Cell(70, 7, 'Guten Tag (GOO-ten tahk)', 0, 0);
$pdf->Cell(10, 7, 'a.', 0, 0);
$pdf->Cell(70, 7, 'Please', 0, 1);

$pdf->Cell(10, 7, '2.', 0, 0);
$pdf->Cell(70, 7, 'Danke (DAHN-kuh)', 0, 0);
$pdf->Cell(10, 7, 'b.', 0, 0);
$pdf->Cell(70, 7, 'Hello', 0, 1);

$pdf->Cell(10, 7, '3.', 0, 0);
$pdf->Cell(70, 7, 'Bitte (BIT-tuh)', 0, 0);
$pdf->Cell(10, 7, 'c.', 0, 0);
$pdf->Cell(70, 7, 'Thank you', 0, 1);

$pdf->Cell(10, 7, '4.', 0, 0);
$pdf->Cell(70, 7, 'Wie geht es Ihnen? (vee gayt es EE-nen)', 0, 0);
$pdf->Cell(10, 7, 'd.', 0, 0);
$pdf->Cell(70, 7, 'Goodbye', 0, 1);

$pdf->Cell(10, 7, '5.', 0, 0);
$pdf->Cell(70, 7, 'Auf Wiedersehen (owf VEE-der-zay-en)', 0, 0);
$pdf->Cell(10, 7, 'e.', 0, 0);
$pdf->Cell(70, 7, 'How are you?', 0, 1);
$pdf->Ln(5);

$pdf->SectionTitle('Fill in the Blanks:');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 7, 'Complete the sentences with the correct form of "sein" or "haben":', 0, 1);
$pdf->Ln(2);
$pdf->BulletPoint("Ich _____ Student. (I am a student)");
$pdf->BulletPoint("Wie alt _____ du? (How old are you?)");
$pdf->BulletPoint("Das Haus _____ groß. (The house is big)");
$pdf->BulletPoint("Wir _____ in Berlin. (We are in Berlin)");
$pdf->Ln(5);

$pdf->SectionTitle('Translation Practice:');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 7, 'Translate the following sentences to German:', 0, 1);
$pdf->Ln(2);
$pdf->BulletPoint("My name is John.");
$pdf->BulletPoint("I am twenty years old.");
$pdf->BulletPoint("Where is the restaurant?");
$pdf->BulletPoint("I would like a coffee, please.");
$pdf->BulletPoint("How much does it cost?");

// Reference Page
$pdf->AddPage();
$pdf->LessonTitle('', 'Quick Reference');

$pdf->SetFont('Arial', 'I', 11);
$pdf->MultiCell(0, 7, "A handy summary of the most important phrases and vocabulary with pronunciation.");
$pdf->Ln(3);

$pdf->SectionTitle('Essential Phrases at a Glance:');
$ref_german = [
    'Guten Tag/Auf Wiedersehen', 
    'Bitte/Danke',
    'Ja/Nein', 
    'Entschuldigung',
    'Ich verstehe nicht', 
    'Sprechen Sie Englisch?'
];
$ref_pron = [
    'GOO-ten tahk/owf VEE-der-zay-en', 
    'BIT-tuh/DAHN-kuh',
    'yah/nyne', 
    'ent-SHOOL-di-goong',
    'ikh fer-SHTAY-uh nikht', 
    'SHPREH-khen zee ENG-lish'
];
$ref_phrases = [
    'Hello/Goodbye', 
    'Please/Thank you',
    'Yes/No', 
    'Sorry/Excuse me',
    'I don\'t understand', 
    'Do you speak English?'
];
$pdf->PronunciationTable($ref_german, $ref_pron, $ref_phrases);

$pdf->SectionTitle('Pronunciation Guide:');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 7, "Remember these key German pronunciation rules:\n");
$pdf->BulletPoint("German is phonetically consistent - it's pronounced as it's spelled");
$pdf->BulletPoint("The letter 'v' is pronounced like 'f' in English");
$pdf->BulletPoint("The letter 'w' is pronounced like 'v' in English");
$pdf->BulletPoint("The letter 'j' is pronounced like 'y' in English");
$pdf->BulletPoint("The letter 'z' is pronounced like 'ts'");
$pdf->BulletPoint("The combination 'ch' is a soft sound made at the back of the throat");
$pdf->BulletPoint("The combination 'sch' is pronounced like 'sh' in English");
$pdf->BulletPoint("Umlauts (ä, ö, ü) modify the pronunciation of vowels");
$pdf->BulletPoint("The double 's' (ß) is pronounced like a sharp 's' sound");
$pdf->BulletPoint("German nouns are always capitalized regardless of their position in the sentence");
$pdf->BulletPoint("German has three grammatical genders: masculine (der), feminine (die), and neuter (das)");

// Output PDF
$pdf->Output('D', 'German_Course.pdf');
exit;
?>