-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05-Maio-2023 às 21:33
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `chat_bot_db`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `entity`
--

CREATE TABLE `chat_bot_entity` (
  `id` int(11) NOT NULL,
  `entity` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `entity`
--

INSERT INTO `chat_bot_entity` (`id`, `entity`, `role`) VALUES
(1, 'Ficha_Individual_de_Imoveis', 'Ficha_Individual_de_Imoveis'),
(2, 'excluir_um_imovel', 'excluir_um_imovel'),
(3, 'relacao_de_cadastros', 'relacao_de_cadastros'),
(4, 'relacao_de_imoveis', 'relacao_de_imoveis'),
(5, 'site_no_ar', 'site_no_ar');

-- --------------------------------------------------------

--
-- Estrutura da tabela `keyword_fetched`
--

CREATE TABLE `chat_bot_keyword_fetched` (
  `response_id` int(30) NOT NULL,
  `client` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `keyword_fetched`
--

INSERT INTO `chat_bot_keyword_fetched` (`response_id`, `client`) VALUES
(3, '::1'),
(4, '::1'),
(6, '::1'),
(7, '::1'),
(7, '::1'),
(7, '::1'),
(7, '::1'),
(7, '::1'),
(9, '::1'),
(9, '::1'),
(7, '::1'),
(6, '::1'),
(7, '::1'),
(6, '::1'),
(7, '::1'),
(6, '::1'),
(6, '::1'),
(7, '::1'),
(5, '::1'),
(9, '::1'),
(7, '::1'),
(6, '::1'),
(6, '::1'),
(6, '::1'),
(5, '::1'),
(9, '::1'),
(8, '::1'),
(4, '::1'),
(7, '::1'),
(6, '::1'),
(5, '::1'),
(7, '::1'),
(6, '::1'),
(7, '::1'),
(6, '::1'),
(7, '::1'),
(6, '::1'),
(7, '::1'),
(7, '::1'),
(8, '::1'),
(4, '::1'),
(7, '::1'),
(8, '::1'),
(4, '::1'),
(9, '::1'),
(8, '::1'),
(4, '::1'),
(9, '::1'),
(8, '::1'),
(4, '::1'),
(9, '::1'),
(8, '::1'),
(4, '::1'),
(7, '::1'),
(8, '::1'),
(4, '::1'),
(5, '::1'),
(5, '::1'),
(5, '::1'),
(7, '::1'),
(8, '::1'),
(4, '::1'),
(7, '::1'),
(8, '::1'),
(4, '::1'),
(9, '::1'),
(8, '::1'),
(4, '::1'),
(9, '::1'),
(8, '::1'),
(4, '::1'),
(5, '::1'),
(9, '::1'),
(8, '::1'),
(4, '::1'),
(7, '::1'),
(8, '::1'),
(4, '::1'),
(5, '::1'),
(7, '::1'),
(8, '::1'),
(4, '::1'),
(9, '::1'),
(8, '::1'),
(4, '::1'),
(9, '::1'),
(9, '::1'),
(9, '::1'),
(9, '::1'),
(9, '::1'),
(9, '::1'),
(9, '::1'),
(9, '::1'),
(9, '::1'),
(9, '::1'),
(9, '::1'),
(9, '::1'),
(9, '::1'),
(9, '::1'),
(9, '::1'),
(3, '::1');

-- --------------------------------------------------------

--
-- Estrutura da tabela `keyword_list`
--

CREATE TABLE `chat_bot_keyword_list` (
  `id` int(11) NOT NULL,
  `response_id` int(30) NOT NULL,
  `keyword` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `keyword_list`
--

INSERT INTO `chat_bot_keyword_list` (`id`, `response_id`, `keyword`) VALUES
(1, 3, 'Sample Query 1'),
(2, 3, 'Sample Query 2'),
(3, 3, 'Sample Query 3'),
(7, 8, 'Entendi, obrigado'),
(8, 5, 'Olá'),
(9, 5, 'Oi'),
(10, 5, 'Bom dia'),
(11, 5, 'Boa tarde'),
(12, 5, 'Boa noite'),
(13, 4, 'Tenho outra dúvida'),
(14, 6, 'Quero saber mais'),
(15, 7, 'Como funciona?'),
(16, 7, 'Como funciona'),
(17, 7, 'Quero um site'),
(18, 7, 'Preciso de um site'),
(19, 7, 'Como faço para ter um site?'),
(20, 9, 'Como colocar meu site no ar?'),
(21, 9, 'Quero colocar o site no ar'),
(22, 9, 'Site no ar');

-- --------------------------------------------------------

--
-- Estrutura da tabela `response_list`
--

CREATE TABLE `chat_bot_response_list` (
  `id` int(30) NOT NULL,
  `response` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `entity` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `response_list`
--

INSERT INTO `chat_bot_response_list` (`id`, `response`, `status`, `entity`, `date_created`, `date_updated`) VALUES
(3, '<p><span style=\"color: rgb(0, 0, 0); font-family: \"Open Sans\", Arial, sans-serif; text-align: justify;\">Nam eget fermentum quam. Sed risus dolor, gravida ac faucibus non, facilisis in odio. Etiam quis felis quis ipsum eleifend consectetur et at elit. In mattis ullamcorper lorem ac dictum.</span><br></p>', 1, '', '2022-05-05 11:38:44', '2022-05-05 12:54:28'),
(4, '<p><span style=\"color: rgb(0, 0, 0); font-family: \" open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" text-align:=\"\" justify;\"=\"\">Certo, pode perguntar.</span><br></p>', 1, '', '2022-05-05 14:40:29', '2023-05-05 08:21:22'),
(5, '<p><span style=\"color: rgb(0, 0, 0); font-family: \" open=\"\" sans\",=\"\" arial,=\"\" sans-serif;=\"\" text-align:=\"\" justify;\"=\"\">Estou aqui para te ajudar, qual a sua dúvida?</span><br></p>', 1, '', '2022-05-05 14:41:00', '2023-05-05 08:21:07'),
(6, '<p class=\"MsoNormal\" style=\"margin: 5pt 0in; line-height: normal; border: none; padding: 0in;\">Temos planos a partir de R$ 39,99 mensal, para verificar os detalhes de cada um acesse nossa página de planos: https://imobibrasil.com.br/planos.php<br>*O pagamento é feito apenas via boleto bancário, para qualquer um dos planos.<o:p></o:p></p><p class=\"MsoNormal\" style=\"margin: 5pt 0in; line-height: normal; border: none; padding: 0in;\"><br>Caso você não possua o domínio (endereço www.seudominio.com.br) registrado, o valor é de R$ 40,00 para fim COM.BR, pago anualmente e diretamente para o Órgão RegistroBr (responsável pelos endereços www).<o:p></o:p></p><p class=\"MsoNormal\" style=\"margin: 5pt 0in; line-height: normal; border: none; padding: 0in;\"><br>No ImobiBrasil não há taxa de inscrição, não há multa rescisória, não há contrato mínimo e não há outros valores na mensalidade. Faça o teste por sete dias gratuitamente!  <o:p></o:p></p><p class=\"MsoNormal\" style=\"margin: 5pt 0in; line-height: normal; border: none; padding: 0in;\"><br>Acesse o nosso site www.imobibrasil.com.br, clique no item “Experimente Já” e faça o cadastro!</p><p class=\"MsoNormal\" style=\"margin: 5pt 0in; line-height: normal; border: none; padding: 0in;\"><br></p><p class=\"MsoNormal\" style=\"margin: 5pt 0in; line-height: normal; border: none; padding: 0in;\">Segue nosso WhatsApp para tirar dúvidas: (18) 99646-9265.</p>', 1, '', '2022-05-05 14:41:36', '2023-05-05 08:23:47'),
(7, '<p class=\"MsoNormal\" style=\"margin: 5pt 0in; line-height: normal; border: none; padding: 0in;\">Nosso site conta com 40 modelos diferentes, com diversas opções de cores. Você insere os imóveis, personaliza, e coloca todas as informações que achar relevante. Te entregamos o sistema pronto para inserção das informações. Caso o cliente não tenha logotipo, temos alguns modelos prontos, dos quais adequamos as cores para combinar com o site do cliente.</p><p class=\"MsoNormal\" style=\"margin: 5pt 0in; line-height: normal; border: none; padding: 0in;\"><br></p><p class=\"MsoNormal\" style=\"margin: 5pt 0in; line-height: normal; border: none; padding: 0in;\">Segue nosso WhatsApp para tirar dúvidas: (18) 99646-9265.</p>', 1, '', '2022-05-05 15:19:35', '2023-05-05 08:04:21'),
(8, '<p>Por nada, qualquer coisa só chamar.</p>', 1, '', '2022-05-05 15:31:31', '2023-05-05 08:20:07'),
(9, '<p><span style=\"color: rgb(0, 0, 0); font-family: Arial; font-size: 16px;\">No sistema administrativo, disponibilizamos o&nbsp;</span><strong style=\"margin: 0px; padding: 0px; outline: 0px; color: rgb(0, 0, 0); font-family: \" open=\"\" sans\";=\"\" font-size:=\"\" 16px;\"=\"\"><span style=\"font-family: Arial;\">domínio provisório</span></strong><span style=\"color: rgb(0, 0, 0); font-family: Arial; font-size: 16px;\">&nbsp;para você conseguir visualizar como o seu site está ficando, porém, ainda não é possível visualizar o site pelo celular e nem compartilhar com ninguém.</span><br></p><p><img style=\"width: 471px;\" src=\"data:image/png;base64,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\" data-filename=\"image.png\"></p><p><br></p><h2 class=\"wp-block-heading\" id=\"como-solicitar-que-voces-registrem-o-dominio\" data-id=\"articleTOC_1\" style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; padding: 0px; outline: 0px; overflow-wrap: break-word; font-family: &quot;Open Sans&quot;; font-size: 2.625em; line-height: 1.143; color: rgb(51, 51, 51);\"><b><span style=\"font-family: &quot;Arial Black&quot;;\">Como solicitar que vocês registrem o domínio ?</span></b></h2><p style=\"margin-right: 0px; margin-bottom: 16px; margin-left: 0px; padding: 0px; outline: 0px; font-family: &quot;Open Sans&quot;; font-size: 16px; line-height: 1.6; color: rgb(0, 0, 0) !important;\">Para isso, basta solicitar por e-mail através de seu e-mail de cadastro, informando seu nome completo e CPF ou CNPJ.<br style=\"margin: 0px; padding: 0px; outline: 0px;\">Esse procedimento só pode ser feito quando ele é solicitado pelo suporte do sistema ou por e-mail, através de seu e-mail de cadastro, para&nbsp;<strong style=\"margin: 0px; padding: 0px; outline: 0px;\">contato@imobibrasil.com.br</strong></p><p><br></p>', 1, 'site_no_ar', '2023-05-04 22:48:13', '2023-05-05 13:14:01');

-- --------------------------------------------------------

--
-- Estrutura da tabela `suggestion_list`
--

CREATE TABLE `chat_bot_suggestion_list` (
  `response_id` int(30) NOT NULL,
  `suggestion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `suggestion_list`
--

INSERT INTO `chat_bot_suggestion_list` (`response_id`, `suggestion`) VALUES
(3, 'Suggestion 1'),
(3, 'Suggestion 2'),
(8, 'Tenho outra dúvida'),
(5, 'Como funciona?'),
(5, 'Como colocar meu site no ar?'),
(5, 'Quero colocar o site no ar'),
(4, 'Como funciona?'),
(4, 'Como colocar meu site no ar?'),
(4, 'Quero colocar o site no ar'),
(7, 'Entendi, obrigado'),
(9, 'Entendi, obrigado');

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_info`
--

CREATE TABLE `chat_bot_system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `system_info`
--

INSERT INTO `chat_bot_system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'Chat Bot - Imobibrasil'),
(6, 'short_name', 'Chat Bot - Imobibrasil'),
(11, 'logo', 'uploads/logo.png?v=1651712009'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover.png?v=1651712184'),
(15, 'no_answer', '<p>Desculpe, Não tenho nenhuma resposta para sua consulta. Tente reformular ou reescrever sua consulta. Obrigado!</p>'),
(16, 'suggestion', '[\"Como fa\\u00e7o para ter um site?\",\"Ol\\u00e1\",\"Oi\"]'),
(17, 'welcome_message', '<p>Olá, bem vindo ao nosso site, como posso ajudar?</p>'),
(18, 'bot_name', 'Chatbot Imobi');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `chat_bot_users` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `active` int(2) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `chat_bot_users` (`id`, `firstname`, `lastname`, `username`, `email`, `password`, `avatar`, `last_login`, `type`, `active`, `date_added`, `date_updated`) VALUES
(1, 'Adminstrator', 'Admin', 'admin', 'marlon@gmail.com', '0192023a7bbd73250516f069df18b500', 'uploads/avatars/1.png?v=1649834664', NULL, 1, 1, '2021-01-20 14:02:37', '2023-05-05 00:07:21'),
(4, 'Mark', 'Cooper', 'mcooper', 'marlon2@gmail.com', 'c7162ff89c647f444fcaa5c635dac8c3', 'uploads/avatars/4.png?v=1651645642', NULL, 2, 1, '2022-05-04 14:27:21', '2023-05-05 00:07:23');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `entity`
--
ALTER TABLE `chat_bot_entity`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `keyword_fetched`
--
ALTER TABLE `chat_bot_keyword_fetched`
  ADD KEY `response_id` (`response_id`);

--
-- Índices para tabela `keyword_list`
--
ALTER TABLE `chat_bot_keyword_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `response_id` (`response_id`);

--
-- Índices para tabela `response_list`
--
ALTER TABLE `chat_bot_response_list`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `suggestion_list`
--
ALTER TABLE `chat_bot_suggestion_list`
  ADD KEY `response_id` (`response_id`);

--
-- Índices para tabela `system_info`
--
ALTER TABLE `chat_bot_system_info`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `chat_bot_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `entity`
--
ALTER TABLE `chat_bot_entity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `keyword_list`
--
ALTER TABLE `chat_bot_keyword_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `response_list`
--
ALTER TABLE `chat_bot_response_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `system_info`
--
ALTER TABLE `chat_bot_system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `chat_bot_users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `keyword_fetched`
--
ALTER TABLE `chat_bot_keyword_fetched`
  ADD CONSTRAINT `response_id_fk_kf` FOREIGN KEY (`response_id`) REFERENCES `response_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `keyword_list`
--
ALTER TABLE `chat_bot_keyword_list`
  ADD CONSTRAINT `response_id_fk_kl` FOREIGN KEY (`response_id`) REFERENCES `response_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `suggestion_list`
--
ALTER TABLE `chat_bot_suggestion_list`
  ADD CONSTRAINT `response_id_fk_sl` FOREIGN KEY (`response_id`) REFERENCES `response_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
