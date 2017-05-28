<?php if (!defined('IN_PHPBB')) exit; ?>Subject: Üdvözlünk a "<?php echo (isset($this->_rootref['SITENAME'])) ? $this->_rootref['SITENAME'] : ''; ?>" fórumon!

<?php echo (isset($this->_rootref['WELCOME_MSG'])) ? $this->_rootref['WELCOME_MSG'] : ''; ?>


Kérünk, tartsd meg ezt az üzenetet. Az azonosítód adatai a következőek:

----------------------------
Felhasználónév: <?php echo (isset($this->_rootref['USERNAME'])) ? $this->_rootref['USERNAME'] : ''; ?>


Fórum URL: <?php echo (isset($this->_rootref['U_BOARD'])) ? $this->_rootref['U_BOARD'] : ''; ?>

----------------------------

A jelszavad biztonságosan került eltárolásra az adatbázisunkban, így nincs mód a kiderítésére. Ha esetleg mégis elfelejtenéd, akkor az azonosítódhoz társított e-mail cím segítségével igényelhetsz egy újat.

Köszönjük, hogy regisztráltál!

<?php echo (isset($this->_rootref['EMAIL_SIG'])) ? $this->_rootref['EMAIL_SIG'] : ''; ?>