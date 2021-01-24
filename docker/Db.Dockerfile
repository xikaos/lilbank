FROM mysql:5.7
RUN rm -f /etc/localtime && \
	ln -s /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime