<?php if (!defined('IN_PHPBB')) exit; ?>Subject: Témafigyelés értesítés - "<?php echo (isset($this->_rootref['FORUM_NAME'])) ? $this->_rootref['FORUM_NAME'] : ''; ?>"

Kedves <?php echo (isset($this->_rootref['USERNAME'])) ? $this->_rootref['USERNAME'] : ''; ?>!

Ezt az e-mailt azért kapod, mert a "<?php echo (isset($this->_rootref['SITENAME'])) ? $this->_rootref['SITENAME'] : ''; ?>" oldalon a "<?php echo (isset($this->_rootref['TOPIC_TITLE'])) ? $this->_rootref['TOPIC_TITLE'] : ''; ?>" téma figyelését kérted. A témába a legutóbbi látogatásod óta új hozzászólás érkezett<?php if ($this->_rootref['AUTHOR_NAME'] !== ('')) {  ?>, melynek szerzője "<?php echo (isset($this->_rootref['AUTHOR_NAME'])) ? $this->_rootref['AUTHOR_NAME'] : ''; ?>"<?php } ?>. A következő linkre kattintva megtekintheted az azóta született hozzászólásokat. Több értesítést nem fogsz kapni, amíg meg nem tekinted a témát.

Az utolsó látogatás óta érkezett legfrissebb hozzászólás megtekintéséhez kattints a következő linkre:
<?php echo (isset($this->_rootref['U_NEWEST_POST'])) ? $this->_rootref['U_NEWEST_POST'] : ''; ?>


A téma megtekintéséhez kattints a következő linkre:
<?php echo (isset($this->_rootref['U_TOPIC'])) ? $this->_rootref['U_TOPIC'] : ''; ?>


A fórum megtekintéséhez kattints a következő linkre:
<?php echo (isset($this->_rootref['U_FORUM'])) ? $this->_rootref['U_FORUM'] : ''; ?>


Amennyiben a továbbiakban nem kívánod figyelni a témát, kattints a "Leiratkozás a témáról" linkre, melyet a fenti oldalon találsz, vagy egyszerűen kattints a következő linkre:

<?php echo (isset($this->_rootref['U_STOP_WATCHING_TOPIC'])) ? $this->_rootref['U_STOP_WATCHING_TOPIC'] : ''; ?>


<?php echo (isset($this->_rootref['EMAIL_SIG'])) ? $this->_rootref['EMAIL_SIG'] : ''; ?>